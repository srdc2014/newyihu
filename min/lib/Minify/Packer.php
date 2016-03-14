<?php
if (false === (@include 'class.JavaScriptPacker.php')) {
    trigger_error(
        'The script "class.JavaScriptPacker.php" is required. Please see: http:'
        .'//code.google.com/p/minify/source/browse/trunk/min/lib/Minify/Packer.php'
        ,E_USER_ERROR
    );
}
 * Minify Javascript using Dean Edward's Packer
 * @package Minify
class Minify_Packer {
    public static function minify($code, $options = array())
    {
        // @todo: set encoding options based on $options :)
        $packer = new JavascriptPacker($code, 'Normal', true, false);
        return trim($packer->pack());
    }
}
