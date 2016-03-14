<?php
class Minify_HTML_Helper {
    public $rewriteWorks = true;
    public $minAppUri = '/min';
    public $groupsConfigFile = '';
    public static function getUri($keyOrFiles, $opts = array())
    {
        $opts = array_merge(array( 
            'farExpires' => true
            ,'debug' => false
            ,'charset' => 'UTF-8'
            ,'minAppUri' => '/min'
            ,'rewriteWorks' => true
            ,'groupsConfigFile' => ''
        ), $opts);
        $h = new self;
        $h->minAppUri = $opts['minAppUri'];
        $h->rewriteWorks = $opts['rewriteWorks'];
        $h->groupsConfigFile = $opts['groupsConfigFile'];
        if (is_array($keyOrFiles)) {
            $h->setFiles($keyOrFiles, $opts['farExpires']);
        } else {
            $h->setGroup($keyOrFiles, $opts['farExpires']);
        }
        $uri = $h->getRawUri($opts['farExpires'], $opts['debug']);
        return htmlspecialchars($uri, ENT_QUOTES, $opts['charset']);
    }
    public function getRawUri($farExpires = true, $debug = false)
    {
        $path = rtrim($this->minAppUri, '/') . '/';
        if (! $this->rewriteWorks) {
            $path .= '?';
        }
        if (null === $this->_groupKey) {
            $path = self::_getShortestUri($this->_filePaths, $path);
        } else {
            $path .= "g=" . $this->_groupKey;
        }
        if ($debug) {
            $path .= "&debug";
        } elseif ($farExpires && $this->_lastModified) {
            $path .= "&" . $this->_lastModified;
        }
        return $path;
    }
     * Set the files that will comprise the URI we're building
     * @param array $files
     * @param bool $checkLastModified
    public function setFiles($files, $checkLastModified = true)
    {
        $this->_groupKey = null;
        if ($checkLastModified) {
            $this->_lastModified = self::getLastModified($files);
        }
        // normalize paths like in /min/f=<paths>
        foreach ($files as $k => $file) {
            if (0 === strpos($file, '
                $file = substr($file, strlen($_SERVER['DOCUMENT_ROOT']) + 1);
            }
            $file = strtr($file, '\\', '/');
            $files[$k] = $file;
        }
        $this->_filePaths = $files;
    }
     * Set the group of files that will comprise the URI we're building
     * @param string $key
     * @param bool $checkLastModified
    public function setGroup($key, $checkLastModified = true)
    {
        $this->_groupKey = $key;
        if ($checkLastModified) {
            if (! $this->groupsConfigFile) {
                $this->groupsConfigFile = dirname(dirname(dirname(dirname(__FILE__)))) . '/groupsConfig.php';
            }
            if (is_file($this->groupsConfigFile)) {
                $gc = (require $this->groupsConfigFile);
                $keys = explode(',', $key);
                foreach ($keys as $key) {
                    if (isset($gc[$key])) {
                        $this->_lastModified = self::getLastModified($gc[$key], $this->_lastModified);
                    }
                }
            }
        }
    }
     * Get the max(lastModified) of all files
     * @param array|string $sources
     * @param int $lastModified
     * @return int
    public static function getLastModified($sources, $lastModified = 0)
    {
        $max = $lastModified;
        foreach ((array)$sources as $source) {
            if (is_object($source) && isset($source->lastModified)) {
                $max = max($max, $source->lastModified);
            } elseif (is_string($source)) {
                if (0 === strpos($source, '
            $uri = strlen($uri) < strlen($bUri)
                ? $uri
                : $bUri;
        }
        return $uri;
    }
}
