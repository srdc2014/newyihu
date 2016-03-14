<?php
class Minify_HTML {
    protected $_jsCleanComments = true;
    public static function minify($html, $options = array()) {
        $min = new self($html, $options);
        return $min->process();
    }
    public function __construct($html, $options = array())
    {
        $this->_html = str_replace("\r\n", "\n", trim($html));
        if (isset($options['xhtml'])) {
            $this->_isXhtml = (bool)$options['xhtml'];
        }
        if (isset($options['cssMinifier'])) {
            $this->_cssMinifier = $options['cssMinifier'];
        }
        if (isset($options['jsMinifier'])) {
            $this->_jsMinifier = $options['jsMinifier'];
        }
        if (isset($options['jsCleanComments'])) {
            $this->_jsCleanComments = (bool)$options['jsCleanComments'];
        }
    }
    public function process()
    {
        if ($this->_isXhtml === null) {
            $this->_isXhtml = (false !== strpos($this->_html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML'));
        }
        $this->_replacementHash = 'MINIFYHTML' . md5($_SERVER['REQUEST_TIME']);
        $this->_placeholders = array();
        $this->_html = preg_replace_callback(
            '/(\\s*)<script(\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
            ,array($this, '_removeScriptCB')
            ,$this->_html);
        $this->_html = preg_replace_callback(
            '/\\s*<style(\\b[^>]*>)([\\s\\S]*?)<\\/style>\\s*/i'
            ,array($this, '_removeStyleCB')
            ,$this->_html);
        $this->_html = preg_replace_callback(
            '/<!--([\\s\\S]*?)-->/'
            ,array($this, '_commentCB')
            ,$this->_html);
        $this->_html = preg_replace_callback('/\\s*<pre(\\b[^>]*?>[\\s\\S]*?<\\/pre>)\\s*/i'
            ,array($this, '_removePreCB')
            ,$this->_html);
        $this->_html = preg_replace_callback(
            '/\\s*<textarea(\\b[^>]*?>[\\s\\S]*?<\\/textarea>)\\s*/i'
            ,array($this, '_removeTextareaCB')
            ,$this->_html);
        $this->_html = preg_replace('/^\\s+|\\s+$/m', '', $this->_html);
        $this->_html = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body'
            .'|caption|center|cite|col(?:group)?|dd|dir|div|dl|dt|fieldset|form'
            .'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta'
            .'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)'
            .'|ul)\\b[^>]*>)/i', '$1', $this->_html);
        $this->_html = preg_replace(
            '/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</'
            ,'>$1$2$3<'
            ,$this->_html);
        $this->_html = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $this->_html);
        $this->_html = str_replace(
            array_keys($this->_placeholders)
            ,array_values($this->_placeholders)
            ,$this->_html
        );
    }
}
