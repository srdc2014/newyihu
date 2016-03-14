<?php
    public static $javaExecutable = 'java';
    public static function minify($js, $options = array())
    {
        self::_prepare();
        if (! ($tmpFile = tempnam(self::$tempDir, 'cc_'))) {
            throw new Exception('Minify_ClosureCompiler : could not create temp file.');
        }
        file_put_contents($tmpFile, $js);
        exec(self::_getCmd($options, $tmpFile), $output, $result_code);
        unlink($tmpFile);
        if ($result_code != 0) {
            throw new Exception('Minify_ClosureCompiler : Closure Compiler execution failed.');
        }
        return implode("\n", $output);
    }
    private static function _getCmd($userOptions, $tmpFile)
    {
        $o = array_merge(
            array(
                'charset' => 'utf-8',
                'compilation_level' => 'SIMPLE_OPTIMIZATIONS',
            ),
            $userOptions
        );
        $cmd = self::$javaExecutable . ' -jar ' . escapeshellarg(self::$jarFile)
             . (preg_match('/^[\\da-zA-Z0-9\\-]+$/', $o['charset'])
                ? " --charset {$o['charset']}"
                : '');
        foreach (array('compilation_level') as $opt) {
            if ($o[$opt]) {
                $cmd .= " --{$opt} ". escapeshellarg($o[$opt]);
            }
        }
        return $cmd . ' ' . escapeshellarg($tmpFile);
    }
    private static function _prepare()
    {
        if (! is_file(self::$jarFile)) {
            throw new Exception('Minify_ClosureCompiler : $jarFile('.self::$jarFile.') is not a valid file.');
        }
        if (! is_readable(self::$jarFile)) {
            throw new Exception('Minify_ClosureCompiler : $jarFile('.self::$jarFile.') is not readable.');
        }
        if (! is_dir(self::$tempDir)) {
            throw new Exception('Minify_ClosureCompiler : $tempDir('.self::$tempDir.') is not a valid direcotry.');
        }
        if (! is_writable(self::$tempDir)) {
            throw new Exception('Minify_ClosureCompiler : $tempDir('.self::$tempDir.') is not writable.');
        }
    }
}
