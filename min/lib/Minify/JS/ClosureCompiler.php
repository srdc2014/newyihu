<?php
    protected function _fallback($js)
    {
        return JSMin::minify($js);
    }
}
class Minify_JS_ClosureCompiler_Exception extends Exception {}
