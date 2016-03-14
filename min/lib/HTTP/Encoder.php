<?php
class HTTP_Encoder {
    public static $encodeToIe6 = true;
    public static $compressionLevel = 6;
    public static function getAcceptedEncoding($allowCompress = true, $allowDeflate = true)
    {
        if (! isset($_SERVER['HTTP_ACCEPT_ENCODING'])
            || self::isBuggyIe())
        {
            return array('', '');
        }
        $ae = $_SERVER['HTTP_ACCEPT_ENCODING'];
        if (0 === strpos($ae, 'gzip,')             
            || 0 === strpos($ae, 'deflate, gzip,') 
        ) {
            return array('gzip', 'gzip');
        }
        if (preg_match(
                '@(?:^|,)\\s*((?:x-)?gzip)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@'
                ,$ae
                ,$m)) {
            return array('gzip', $m[1]);
        }
        if ($allowDeflate) {
            $aeRev = strrev($ae);
            if (0 === strpos($aeRev, 'etalfed ,') 
                || 0 === strpos($aeRev, 'etalfed,') 
                || 0 === strpos($ae, 'deflate,') 
                || preg_match(
                    '@(?:^|,)\\s*deflate\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae)) {
                return array('deflate', 'deflate');
            }
        }
        if ($allowCompress && preg_match(
                '@(?:^|,)\\s*((?:x-)?compress)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@'
                ,$ae
                ,$m)) {
            return array('compress', $m[1]);
        }
        return array('', '');
    }
     * Encode (compress) the content
     * If the encode method is '' (none) or compression level is 0, or the 'zlib'
     * extension isn't loaded, we return false.
     * Then the appropriate gz_* function is called to compress the content. If
     * this fails, false is returned.
     * The header "Vary: Accept-Encoding" is added. If encoding is successful, 
     * the Content-Length header is updated, and Content-Encoding is also added.
     * @param int $compressionLevel given to zlib functions. If not given, the
     * class default will be used.
     * @return bool success true if the content was actually compressed
    public function encode($compressionLevel = null)
    {
        if (! self::isBuggyIe()) {
            $this->_headers['Vary'] = 'Accept-Encoding';
        }
        if (null === $compressionLevel) {
            $compressionLevel = self::$compressionLevel;
        }
        if ('' === $this->_encodeMethod[0]
            || ($compressionLevel == 0)
            || !extension_loaded('zlib'))
        {
            return false;
        }
        if ($this->_encodeMethod[0] === 'deflate') {
            $encoded = gzdeflate($this->_content, $compressionLevel);
        } elseif ($this->_encodeMethod[0] === 'gzip') {
            $encoded = gzencode($this->_content, $compressionLevel);
        } else {
            $encoded = gzcompress($this->_content, $compressionLevel);
        }
        if (false === $encoded) {
            return false;
        }
        $this->_headers['Content-Length'] = $this->_useMbStrlen
            ? (string)mb_strlen($encoded, '8bit')
            : (string)strlen($encoded);
        $this->_headers['Content-Encoding'] = $this->_encodeMethod[1];
        $this->_content = $encoded;
        return true;
    }
     * Encode and send appropriate headers and content
     * This is a convenience method for common use of the class
     * @param string $content
     * @param int $compressionLevel given to zlib functions. If not given, the
     * class default will be used.
     * @return bool success true if the content was actually compressed
    public static function output($content, $compressionLevel = null)
    {
        if (null === $compressionLevel) {
            $compressionLevel = self::$compressionLevel;
        }
        $he = new HTTP_Encoder(array('content' => $content));
        $ret = $he->encode($compressionLevel);
        $he->sendAll();
        return $ret;
    }
     * Is the browser an IE version earlier than 6 SP2?
     * @return bool
    public static function isBuggyIe()
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $ua = $_SERVER['HTTP_USER_AGENT'];
        // quick escape for non-IEs
        if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
            || false !== strpos($ua, 'Opera')) {
            return false;
        }
        // no regex = faaast
        $version = (float)substr($ua, 30);
        return self::$encodeToIe6
            ? ($version < 6 || ($version == 6 && false === strpos($ua, 'SV1')))
            : ($version < 7);
    }
    protected $_content = '';
    protected $_headers = array();
    protected $_encodeMethod = array('', '');
    protected $_useMbStrlen = false;
}
