<?php
    public final function analyzeSources($options = array()) 
    {
        if ($this->sources) {
            if (! isset($options['contentType'])) {
                $options['contentType'] = Minify_Source::getContentType($this->sources);
            }
            if (! isset($options['lastModifiedTime'])) {
                $max = 0;
                foreach ($this->sources as $source) {
                    $max = max($source->lastModified, $max);
                }
                $options['lastModifiedTime'] = $max;
            }    
        }
        return $options;
    }
    public function log($msg) {
        Minify_Logger::log($msg);
    }
}
