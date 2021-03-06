<?php
class Minify_Controller_MinApp extends Minify_Controller_Base {
    public function setupSources($options) {
            ) {
                $this->log("GET param 'f' was invalid");
                return $options;
            }
            $ext = ".{$m[1]}";
            try {
                $this->checkType($m[1]);
            } catch (Exception $e) {
                $this->log($e->getMessage());
                return $options;
            }
            $files = explode(',', $_GET['f']);
            if ($files != array_unique($files)) {
                $this->log("Duplicate files were specified");
                return $options;
            }
            if (isset($_GET['b'])) {
                if (preg_match('@^[^/]+(?:/[^/]+)*$@', $_GET['b'])
                    && false === strpos($_GET['b'], '..')
                    && $_GET['b'] !== '.') {
                    $base = "/{$_GET['b']}/";       
                } else {
                    $this->log("GET param 'b' was invalid");
                    return $options;
                }
            } else {
                $base = '/';
            }
            $allowDirs = array();
            foreach ((array)$cOptions['allowDirs'] as $allowDir) {
                $allowDirs[] = realpath(str_replace('//', $_SERVER['DOCUMENT_ROOT'] . '/', $allowDir));
            }
            $basenames = array(); 
            foreach ($files as $file) {
                $uri = $base . $file;
                $path = $_SERVER['DOCUMENT_ROOT'] . $uri;
                $realpath = realpath($path);
                if (false === $realpath || ! is_file($realpath)) {
                    $this->log("The path \"{$path}\" (realpath \"{$realpath}\") could not be found (or was not a file)");
                    if (null === $firstMissingResource) {
                        $firstMissingResource = $uri;
                        continue;
                    } else {
                        $secondMissingResource = $uri;
                        $this->log("More than one file was missing: '$firstMissingResource', '$secondMissingResource`'");
                        return $options;
                    }
                }
                try {
                    parent::checkNotHidden($realpath);
                    parent::checkAllowDirs($realpath, $allowDirs, $uri);
                } catch (Exception $e) {
                    $this->log($e->getMessage());
                    return $options;
                }
                $sources[] = $this->_getFileSource($realpath, $cOptions);
                $basenames[] = basename($realpath, $ext);
            }
            if ($this->selectionId) {
                $this->selectionId .= '_f=';
            }
            $this->selectionId .= implode(',', $basenames) . $ext;
        }
        if ($sources) {
            if (null !== $firstMissingResource) {
                array_unshift($sources, new Minify_Source(array(
                    'id' => 'missingFile'
                    ,'lastModified' => 0
                    ,'content' => "/* Minify: at least one missing file. See " . Minify::URL_DEBUG . " */\n"
                    ,'minifier' => ''
                )));
            }
            $this->sources = $sources;
        } else {
            $this->log("No sources to serve");
        }
        return $options;
    }
    protected function _getFileSource($file, $cOptions)
    {
        $spec['filepath'] = $file;
        if ($cOptions['noMinPattern'] && preg_match($cOptions['noMinPattern'], basename($file))) {
            if (preg_match('~\.css$~i', $file)) {
                $spec['minifyOptions']['compress'] = false;
            } else {
                $spec['minifier'] = '';
            }
        }
        return new Minify_Source($spec);
    }
    protected $_type = null;
    public function checkType($sourceOrExt)
    {
        if ($sourceOrExt === 'js') {
            $type = Minify::TYPE_JS;
        } elseif ($sourceOrExt === 'css') {
            $type = Minify::TYPE_CSS;
        } elseif ($sourceOrExt->contentType !== null) {
            $type = $sourceOrExt->contentType;
        } else {
            return;
        }
        if ($this->_type === null) {
            $this->_type = $type;
        } elseif ($this->_type !== $type) {
            throw new Exception('Content-Type mismatch');
        }
    }
}
