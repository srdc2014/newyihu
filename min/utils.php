<?php
if (! class_exists('Minify_Loader', false)) {
    require dirname(__FILE__) . '/lib/Minify/Loader.php';
    Minify_Loader::register();
}
function Minify_getUri($keyOrFiles, $opts = array())
{
    return Minify_HTML_Helper::getUri($keyOrFiles, $opts);
}
 * Get the last modification time of several source js/css files. If you're
 * caching the output of Minify_getUri(), you might want to know if one of the
 * dependent source files has changed so you can update the HTML.
 * Since this makes a bunch of stat() calls, you might not want to check this
 * on every request.
 * @param array $keysAndFiles group keys and/or file paths/URIs.
 * @return int latest modification time of all given keys/files
function Minify_mtime($keysAndFiles, $groupsConfigFile = null)
{
    $gc = null;
    if (! $groupsConfigFile) {
        $groupsConfigFile = dirname(__FILE__) . '/groupsConfig.php';
    }
    $sources = array();
    foreach ($keysAndFiles as $keyOrFile) {
        if (is_object($keyOrFile)
            || 0 === strpos($keyOrFile, '/')
            || 1 === strpos($keyOrFile, ':\\')) {
            $sources[] = $keyOrFile;
        } else {
            if (! $gc) {
                $gc = (require $groupsConfigFile);
            }
            foreach ($gc[$keyOrFile] as $source) {
                $sources[] = $source;
            }
        }
    }
    return Minify_HTML_Helper::getLastModified($sources);
}
