<?php
$min_enableBuilder = false;
$min_errorLogger = true;
$min_documentRoot = $_SERVER['DOCUMENT_ROOT'];
$min_cacheFileLocking = true;
$min_serveOptions['bubbleCssImports'] = false;
$min_uploaderHoursBehind = 0;
 * Path to Minify's lib folder. If you happen to move it, change
 * this accordingly.
$min_libPath = dirname(__FILE__) . '/lib';
// try to disable output_compression (may not have an effect)
ini_set('zlib.output_compression', '0');
