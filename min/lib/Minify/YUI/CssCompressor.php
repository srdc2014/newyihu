<?php
class Minify_YUI_CssCompressor {
    public function compress($css, $linebreakpos = 0)
    {
        $css = str_replace("\r\n", "\n", $css);
        $css = preg_replace('@\s+@', ' ', $css);
        $css = preg_replace("@\"\\\\\"}\\\\\"\"@", "___PSEUDOCLASSBMH___", $css);
        $css = preg_replace_callback("@(^|\\})(([^\\{:])+:)+([^\\{]*\\{)@", array($this, '_removeSpacesCB'), $css);
        $css = preg_replace("@\\s+([!{};:>+\\(\\)\\],])@", "$1", $css);
        $css = str_replace("___PSEUDOCLASSCOLON___", ":", $css);
        $css = preg_replace("@([!{}:;>+\\(\\[,])\\s+@", "$1", $css);
        $css = preg_replace_callback("@rgb\\s*\\(\\s*([0-9,\\s]+)\\s*\\)@", array($this, '_shortenRgbCB'), $css);
        $css = preg_replace_callback("@([^\"'=\\s])(\\s*)#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])@", array($this, '_shortenHexCB'), $css);
        // Remove empty rules.
        $css = preg_replace("@[^\\}]+\\{;\\}@", "", $css);
        $linebreakpos = isset($this->_options['linebreakpos'])
            ? $this->_options['linebreakpos']
            : 0;
        if ($linebreakpos > 0) {
            // Some source control tools don't like it when files containing lines longer
            // than, say 8000 characters, are checked in. The linebreak option is used in
            // that case to split long lines after a specific column.
            $i = 0;
            $linestartpos = 0;
            $sb = $css;
            // make sure strlen returns byte count
            $mbIntEnc = null;
            if (function_exists('mb_strlen') && ((int)ini_get('mbstring.func_overload') & 2)) {
                $mbIntEnc = mb_internal_encoding();
                mb_internal_encoding('8bit');
            }
            $sbLength = strlen($css);
            while ($i < $sbLength) {
                $c = $sb[$i++];
                if ($c === '}' && $i - $linestartpos > $linebreakpos) {
                    $sb = substr_replace($sb, "\n", $i, 0);
                    $sbLength++;
                    $linestartpos = $i;
                }
            }
            $css = $sb;
            // undo potential mb_encoding change
            if ($mbIntEnc !== null) {
                mb_internal_encoding($mbIntEnc);
            }
        }
        // Replace the pseudo class for the Box Model Hack
        $css = str_replace("___PSEUDOCLASSBMH___", "\"\\\\\"}\\\\\"\"", $css);
        // Replace multiple semi-colons in a row by a single one
        // See SF bug #1980989
        $css = preg_replace("@;;+@", ";", $css);
        // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
        $css = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $css);
        // Trim the final string (for any leading or trailing white spaces)
        $css = trim($css);
        return $css;
    }
    protected function _removeSpacesCB($m)
    {
        return str_replace(':', '___PSEUDOCLASSCOLON___', $m[0]);
    }
    protected function _shortenRgbCB($m)
    {
        $rgbcolors = explode(',', $m[1]);
        $hexcolor = '#';
        for ($i = 0; $i < count($rgbcolors); $i++) {
            $val = round($rgbcolors[$i]);
            if ($val < 16) {
                $hexcolor .= '0';
            }
            $hexcolor .= dechex($val);
        }
        return $hexcolor;
    }
    protected function _shortenHexCB($m)
    {
        // Test for AABBCC pattern
        if ((strtolower($m[3])===strtolower($m[4])) &&
                (strtolower($m[5])===strtolower($m[6])) &&
                (strtolower($m[7])===strtolower($m[8]))) {
            return $m[1] . $m[2] . "#" . $m[3] . $m[5] . $m[7];
        } else {
            return $m[0];
        }
    }
}