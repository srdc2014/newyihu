<?php
class Minify_Controller_Version1 extends Minify_Controller_Base {
    public function setupSources($options) {
            || preg_match('/(?:^|[^\\.])\\.\\//', $_GET['files'])
        ) {
            return $options;
        }
        $files = explode(',', $_GET['files']);
        if (count($files) > MINIFY_MAX_FILES) {
            return $options;
        }
        $prependRelPaths = dirname($_SERVER['SCRIPT_FILENAME'])
            . DIRECTORY_SEPARATOR;
        $prependAbsPaths = $_SERVER['DOCUMENT_ROOT'];
        $goodFiles = array();
        $hasBadSource = false;
        $allowDirs = isset($options['allowDirs'])
            ? $options['allowDirs']
            : MINIFY_BASE_DIR;
        foreach ($files as $file) {
            $file = ($file[0] === '/' ? $prependAbsPaths : $prependRelPaths) . $file;
            $file = realpath($file);
        );
        foreach ($defaults as $const => $val) {
            if (! defined($const)) {
                define($const, $val);
            }
        }
    }
}
