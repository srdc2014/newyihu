<?php
class Minify {
    const VERSION = '2.1.7';
    const TYPE_CSS = 'text/css';
    const TYPE_HTML = 'text/html';
    const TYPE_JS = 'application/x-javascript';
    const URL_DEBUG = 'http://code.google.com/p/minify/wiki/Debugging';
    public static $uploaderHoursBehind = 0;
    public static $importWarning = "/* See http://code.google.com/p/minify/wiki/CommonProblems#@imports_can_appear_in_invalid_locations_in_combined_CSS_files */\n";
    public static $isDocRootSet = false;
    public static function setCache($cache = '', $fileLocking = true)
    {
        if (is_string($cache)) {
            self::$_cache = new Minify_Cache_File($cache, $fileLocking);
        } else {
            self::$_cache = $cache;
        }
    }
    public static function combine($sources, $options = array())
    {
        $cache = self::$_cache;
        self::$_cache = null;
        $options = array_merge(array(
            'files' => (array)$sources
            ,'quiet' => true
            ,'encodeMethod' => ''
            ,'lastModifiedTime' => 0
        ), $options);
        $out = self::serve('Files', $options);
        self::$_cache = $cache;
        return $out['content'];
    }
    public static function setDocRoot($docRoot = '')
    {
        self::$isDocRootSet = true;
        if ($docRoot) {
            $_SERVER['DOCUMENT_ROOT'] = $docRoot;
        } elseif (isset($_SERVER['SERVER_SOFTWARE'])
                  && 0 === strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/')) {
            $_SERVER['DOCUMENT_ROOT'] = substr(
                $_SERVER['SCRIPT_FILENAME']
                ,0
                ,strlen($_SERVER['SCRIPT_FILENAME']) - strlen($_SERVER['SCRIPT_NAME']));
            $_SERVER['DOCUMENT_ROOT'] = rtrim($_SERVER['DOCUMENT_ROOT'], '\\');
        }
    }
     * Any Minify_Cache_* object or null (i.e. no server cache is used)
     * @var Minify_Cache_File
    private static $_cache = null;
     * Active controller for current request
     * @var Minify_Controller_Base
    protected static $_controller = null;
     * Options for current request
     * @var array
    protected static $_options = null;
     * @param string $header
     * @param string $url
    protected static function _errorExit($header, $url)
    {
        $url = htmlspecialchars($url, ENT_QUOTES);
        list(,$h1) = explode(' ', $header, 2);
        $h1 = htmlspecialchars($h1);
        // FastCGI environments require 3rd arg to header() to be set
        list(, $code) = explode(' ', $header, 3);
        header($header, true, $code);
        header('Content-Type: text/html; charset=utf-8');
        echo "<h1>$h1</h1>";
        echo "<p>Please see <a href='$url'>$url</a>.</p>";
        exit();
    }
     * Set up sources to use Minify_Lines
     * @param Minify_Source[] $sources Minify_Source instances
    protected static function _setupDebug($sources)
    {
        foreach ($sources as $source) {
            $source->minifier = array('Minify_Lines', 'minify');
            $id = $source->getId();
            $source->minifyOptions = array(
                'id' => (is_file($id) ? basename($id) : $id)
            );
        }
    }
     * Combines sources and minifies the result.
     * @return string
     * @throws Exception
    protected static function _combineMinify()
    {
        $type = self::$_options['contentType']; // ease readability
        // when combining scripts, make sure all statements separated and
        // trailing single line comment is terminated
        $implodeSeparator = ($type === self::TYPE_JS)
            ? "\n;"
            : '';
        // allow the user to pass a particular array of options to each
        // minifier (designated by type). source objects may still override
        // these
        $defaultOptions = isset(self::$_options['minifierOptions'][$type])
            ? self::$_options['minifierOptions'][$type]
            : array();
        // if minifier not set, default is no minification. source objects
        // may still override this
        $defaultMinifier = isset(self::$_options['minifiers'][$type])
            ? self::$_options['minifiers'][$type]
            : false;
        // process groups of sources with identical minifiers/options
        $content = array();
        $i = 0;
        $l = count(self::$_controller->sources);
        $groupToProcessTogether = array();
        $lastMinifier = null;
        $lastOptions = null;
        do {
            // get next source
            $source = null;
            if ($i < $l) {
                $source = self::$_controller->sources[$i];
                /* @var Minify_Source $source */
                $sourceContent = $source->getContent();
                // allow the source to override our minifier and options
                $minifier = (null !== $source->minifier)
                    ? $source->minifier
                    : $defaultMinifier;
                $options = (null !== $source->minifyOptions)
                    ? array_merge($defaultOptions, $source->minifyOptions)
                    : $defaultOptions;
            }
            // do we need to process our group right now?
            if ($i > 0                               // yes, we have at least the first group populated
                    ! $source                        // yes, we ran out of sources
                    || $type === self::TYPE_CSS      // yes, to process CSS individually (avoiding PCRE bugs/limits)
                    || $minifier !== $lastMinifier   // yes, minifier changed
                    || $options !== $lastOptions)    // yes, options changed
                )
            {
                // minify previous sources with last settings
                $imploded = implode($implodeSeparator, $groupToProcessTogether);
                $groupToProcessTogether = array();
                if ($lastMinifier) {
                    try {
                        $content[] = call_user_func($lastMinifier, $imploded, $lastOptions);
                    } catch (Exception $e) {
                        throw new Exception("Exception in minifier: " . $e->getMessage());
                    }
                } else {
                    $content[] = $imploded;
                }
            }
            // add content to the group
            if ($source) {
                $groupToProcessTogether[] = $sourceContent;
                $lastMinifier = $minifier;
                $lastOptions = $options;
            }
            $i++;
        } while ($source);
        $content = implode($implodeSeparator, $content);
        if ($type === self::TYPE_CSS && false !== strpos($content, '@import')) {
            $content = self::_handleCssImports($content);
        }
        // do any post-processing (esp. for editing build URIs)
        if (self::$_options['postprocessorRequire']) {
            require_once self::$_options['postprocessorRequire'];
        }
        if (self::$_options['postprocessor']) {
            $content = call_user_func(self::$_options['postprocessor'], $content, $type);
        }
        return $content;
    }
     * Make a unique cache id for for this request.
     * Any settings that could affect output are taken into consideration  
     * @param string $prefix
     * @return string
    protected static function _getCacheId($prefix = 'minify')
    {
        $name = preg_replace('/[^a-zA-Z0-9\\.=_,]/', '', self::$_controller->selectionId);
        $name = preg_replace('/\\.+/', '.', $name);
        $name = substr($name, 0, 200 - 34 - strlen($prefix));
        $md5 = md5(serialize(array(
            Minify_Source::getDigest(self::$_controller->sources)
            ,self::$_options['minifiers'] 
            ,self::$_options['minifierOptions']
            ,self::$_options['postprocessor']
            ,self::$_options['bubbleCssImports']
            ,self::VERSION
        )));
        return "{$prefix}_{$name}_{$md5}";
    }
     * Bubble CSS @imports to the top or prepend a warning if an import is detected not at the top.
     * @param string $css
     * @return string
    protected static function _handleCssImports($css)
    {
        if (self::$_options['bubbleCssImports']) {
            // bubble CSS imports
            preg_match_all('/@import.*?;/', $css, $imports);
            $css = implode('', $imports[0]) . preg_replace('/@import.*?;/', '', $css);
        } else if ('' !== self::$importWarning) {
            // remove comments so we don't mistake { in a comment as a block
            $noCommentCss = preg_replace('@/\\*[\\s\\S]*?\\*/@', '', $css);
            $lastImportPos = strrpos($noCommentCss, '@import');
            $firstBlockPos = strpos($noCommentCss, '{');
            if (false !== $lastImportPos
                && false !== $firstBlockPos
                && $firstBlockPos < $lastImportPos
            ) {
                $css = self::$importWarning . $css;
            }
        }
        return $css;
    }
}
