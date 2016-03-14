<?php
class HTTP_ConditionalGet {
    public $cacheIsValid = null;
    public function sendHeaders()
    {
        $headers = $this->_headers;
        if (array_key_exists('_responseCode', $headers)) {
            list(, $code) = explode(' ', $headers['_responseCode'], 3);
            header($headers['_responseCode'], true, $code);
            unset($headers['_responseCode']);
        }
        foreach ($headers as $name => $val) {
            header($name . ': ' . $val);
        }
    }
    protected function resourceMatchedEtag()
    {
        if (!isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            return false;
        }
        $clientEtagList = get_magic_quotes_gpc()
            ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])
            : $_SERVER['HTTP_IF_NONE_MATCH'];
        $clientEtags = explode(',', $clientEtagList);
        $compareTo = $this->normalizeEtag($this->_etag);
        foreach ($clientEtags as $clientEtag) {
            if ($this->normalizeEtag($clientEtag) === $compareTo) {
        list($ifModifiedSince) = explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE'], 2);
        if (strtotime($ifModifiedSince) >= $this->_lmTime) {
            return true;
        }
        return false;
    }
}
