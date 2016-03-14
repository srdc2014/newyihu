<?php
class CSSmin
{
    const NL = '___YUICSSMIN_PRESERVED_NL___';
    const TOKEN = '___YUICSSMIN_PRESERVED_TOKEN_';
    const COMMENT = '___YUICSSMIN_PRESERVE_CANDIDATE_COMMENT_';
    const CLASSCOLON = '___YUICSSMIN_PSEUDOCLASSCOLON___';
    const QUERY_FRACTION = '___YUICSSMIN_QUERY_FRACTION___';
    private $comments;
    private $preserved_tokens;
    private $memory_limit;
    private $max_execution_time;
    private $pcre_backtrack_limit;
    private $pcre_recursion_limit;
    private $raise_php_limits;
    public function __construct($raise_php_limits = TRUE)
    {
        $this->memory_limit = 128 * 1048576; 
        $this->max_execution_time = 60; 
        $this->pcre_backtrack_limit = 1000 * 1000;
        $this->pcre_recursion_limit =  500 * 1000;
        $this->raise_php_limits = (bool) $raise_php_limits;
    }
    public function run($css = '', $linebreak_pos = FALSE)
    {
        if (empty($css)) {
            return '';
        }
        if ($this->raise_php_limits) {
            $this->do_raise_php_limits();
        }
        $this->comments = array();
        $this->preserved_tokens = array();
        $start_index = 0;
        $length = strlen($css);
        $css = $this->extract_data_urls($css);
        while (($start_index = $this->index_of($css, '/*', $start_index)) >= 0) {
            $end_index = $this->index_of($css, '*/', $start_index + 2);
            if ($end_index < 0) {
                $end_index = $length;
            }
            $comment_found = $this->str_slice($css, $start_index + 2, $end_index);
            $this->comments[] = $comment_found;
            $comment_preserve_string = self::COMMENT . (count($this->comments) - 1) . '___';
            $css = $this->str_slice($css, 0, $start_index + 2) . $comment_preserve_string . $this->str_slice($css, $end_index);
            $start_index = $end_index + 2 + strlen($comment_preserve_string) - strlen($comment_found);
        }
        $charset = '';
        $charset_regexp = '/(@charset)( [^;]+;)/i';
        $css_chunks = array();
        $css_chunk_length = 25000; 
        $start_index = 0;
        $i = $css_chunk_length; 
        $l = strlen($css);
        if ($l <= $css_chunk_length) {
            $css_chunks[] = $css;
        } else {
            while ($i < $l) {
                $i += 50; 
                if ($l - $start_index <= $css_chunk_length || $i >= $l) {
                    $css_chunks[] = $this->str_slice($css, $start_index);
                    break;
                }
                if ($css[$i - 1] === '}' && $i - $start_index > $css_chunk_length) {
                    $next_chunk = substr($css, $i);
                    if (preg_match('/^\s*\}/', $next_chunk)) {
                        $i = $i + $this->index_of($next_chunk, '}') + 1;
                    }
                    $css_chunks[] = $this->str_slice($css, $start_index, $i);
                    $start_index = $i;
                }
            }
        }
        for ($i = 0, $n = count($css_chunks); $i < $n; $i++) {
            $css_chunks[$i] = $this->minify($css_chunks[$i], $linebreak_pos);
            if (empty($charset) && preg_match($charset_regexp, $css_chunks[$i], $matches)) {
                $charset = strtolower($matches[1]) . $matches[2];
            }
            $css_chunks[$i] = preg_replace($charset_regexp, '', $css_chunks[$i]);
        }
        $css_chunks[0] = $charset . $css_chunks[0];
        return implode('', $css_chunks);
    }
    public function set_memory_limit($limit)
    {
        $this->memory_limit = $this->normalize_int($limit);
    }
    public function set_max_execution_time($seconds)
    {
        $this->max_execution_time = (int) $seconds;
    }
    public function set_pcre_backtrack_limit($limit)
    {
        $this->pcre_backtrack_limit = (int) $limit;
    }
    public function set_pcre_recursion_limit($limit)
    {
        $this->pcre_recursion_limit = (int) $limit;
    }
    private function do_raise_php_limits()
    {
        $php_limits = array(
            'memory_limit' => $this->memory_limit,
            'max_execution_time' => $this->max_execution_time,
            'pcre.backtrack_limit' => $this->pcre_backtrack_limit,
            'pcre.recursion_limit' =>  $this->pcre_recursion_limit
        );
        foreach ($php_limits as $name => $suggested) {
            $current = $this->normalize_int(ini_get($name));
            if ($current > -1 && ($suggested == -1 || $current < $suggested)) {
                ini_set($name, $suggested);
            }
        }
    }
    private function minify($css, $linebreak_pos)
    {
        for ($i = 0, $max = count($this->comments); $i < $max; $i++) {
            $token = $this->comments[$i];
            $placeholder = '/' . self::COMMENT . $i . '___/';
            if (substr($token, 0, 1) === '!') {
                $this->preserved_tokens[] = $token;
                $token_tring = self::TOKEN . (count($this->preserved_tokens) - 1) . '___';
                $css = preg_replace($placeholder, $token_tring, $css, 1);
                $css = preg_replace('/\s*[\n\r\f]+\s*(\/\*'. $token_tring .')/S', self::NL.'$1', $css);
                $css = preg_replace('/('. $token_tring .'\*\/)\s*[\n\r\f]+\s*/', '$1'.self::NL, $css);
                continue;
            }
            if (substr($token, (strlen($token) - 1), 1) === '\\') {
                $this->preserved_tokens[] = '\\';
                $css = preg_replace($placeholder,  self::TOKEN . (count($this->preserved_tokens) - 1) . '___', $css, 1);
                $i = $i + 1; 
                $this->preserved_tokens[] = '';
                $css = preg_replace('/' . self::COMMENT . $i . '___/',  self::TOKEN . (count($this->preserved_tokens) - 1) . '___', $css, 1);
                continue;
            }
            if (strlen($token) === 0) {
                $start_index = $this->index_of($css, $this->str_slice($placeholder, 1, -1));
                if ($start_index > 2) {
                    if (substr($css, $start_index - 3, 1) === '>') {
                        $this->preserved_tokens[] = '';
                        $css = preg_replace($placeholder,  self::TOKEN . (count($this->preserved_tokens) - 1) . '___', $css, 1);
                    }
                }
            }
            $css = preg_replace('/\/\*' . $this->str_slice($placeholder, 1, -1) . '\*\//', '', $css, 1);
        }
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace_callback('/calc(\(((?:[^\(\)]+|(?1))*)\))/i', array($this, 'replace_calc'), $css);
        $css = preg_replace('/((?<!\\\\)\:|\s)\+(\.?\d+)/S', '$1$2', $css);
        $css = preg_replace('/((?<!\\\\)\:|\s)(\-?)0+(\.?\d+)/S', '$1$2$3', $css);
        $css = preg_replace('/((?<!\\\\)\:|\s)(\-?)(\d?\.\d+?)0+([^\d])/S', '$1$2$3$4', $css);
        $css = preg_replace('/((?<!\\\\)\:|\s)(\-?\d+)\.0([^\d])/S', '$1$2$3', $css);
        $css = preg_replace('/((?<!\\\\)\:|\s)\-?\.?0+([^\d])/S', '${1}0$2', $css);
        $css = preg_replace_callback('/(?:^|\})(?:(?:[^\{\:])+\:)+(?:[^\{]*\{)/', array($this, 'replace_colon'), $css);
        $css = preg_replace('/\s+([\!\{\}\;\:\>\+\(\)\]\~\=,])/', '$1', $css);
        $css = preg_replace('/\!important/i', ' !important', $css);
        $css = preg_replace('/' . self::CLASSCOLON . '/', ':', $css);
        $css = preg_replace_callback('/\:first\-(line|letter)(\{|,)/i', array($this, 'lowercase_pseudo_first'), $css);
        $css = preg_replace('/\*\/ /', '*/', $css);
        $css = preg_replace_callback('/@(font-face|import|(?:-(?:atsc|khtml|moz|ms|o|wap|webkit)-)?keyframe|media|page|namespace)/i', array($this, 'lowercase_directives'), $css);
        $css = preg_replace_callback('/:(active|after|before|checked|disabled|empty|enabled|first-(?:child|of-type)|focus|hover|last-(?:child|of-type)|link|only-(?:child|of-type)|root|:selection|target|visited)/i', array($this, 'lowercase_pseudo_elements'), $css);
        $css = preg_replace_callback('/:(lang|not|nth-child|nth-last-child|nth-last-of-type|nth-of-type|(?:-(?:moz|webkit)-)?any)\(/i', array($this, 'lowercase_common_functions'), $css);
        $css = preg_replace_callback('/rgb\s*\(\s*([0-9,\s\-\.\%]+)\s*\)(.{1})/i', array($this, 'rgb_to_hex'), $css);
        $css = preg_replace_callback('/hsl\s*\(\s*([0-9,\s\-\.\%]+)\s*\)(.{1})/i', array($this, 'hsl_to_hex'), $css);
        $css = $this->compress_hex_colors($css);
        $css = preg_replace('/(border\-?(?:top|right|bottom|left|)|outline)\:none(;|\}| \!)/iS', '$1:0$2', $css);
        $css = preg_replace('/progid\:DXImageTransform\.Microsoft\.Alpha\(Opacity\=/i', 'alpha(opacity=', $css);
        if ($linebreak_pos !== FALSE && (int) $linebreak_pos >= 0) {
            $linebreak_pos = (int) $linebreak_pos;
            $start_index = $i = 0;
            while ($i < strlen($css)) {
                $i++;
                if ($css[$i - 1] === '}' && $i - $start_index > $linebreak_pos) {
                    $css = $this->str_slice($css, 0, $i) . "\n" . $this->str_slice($css, $i);
                    $start_index = $i;
                }
            }
        }
        $css = preg_replace('/;;+/', ';', $css);
        $css = preg_replace('/'. self::NL .'/', "\n", $css);
        $css = preg_replace_callback('/(\{|\;)([A-Z\-]+)(\:)/', array($this, 'lowercase_properties'), $css);
        for ($i = 0, $max = count($this->preserved_tokens); $i < $max; $i++) {
            $css = preg_replace('/' . self::TOKEN . $i . '___/', $this->preserved_tokens[$i], $css, 1);
        }
        return trim($css);
    }
    private function extract_data_urls($css)
    {
        $max_index = strlen($css) - 1;
        $append_index = $index = $last_index = $offset = 0;
        $sb = array();
        $pattern = '/url\(\s*(["\']?)data\:/i';
     * Author: Tubal Martin http:
     * @param string $haystack
     * @param string $needle
     * @param int    $offset index (optional)
     * @return int
    private function index_of($haystack, $needle, $offset = 0)
    {
        $index = strpos($haystack, $needle, $offset);
        return ($index !== FALSE) ? $index : -1;
    }
     * PHP port of Javascript's "slice" function for strings only
     * Author: Tubal Martin http://blog.margenn.com
     * Tests: http://margenn.com/tubal/str_slice/
     * @param string   $str
     * @param int      $start index
     * @param int|bool $end index (optional)
     * @return string
    private function str_slice($str, $start = 0, $end = FALSE)
    {
        if ($end !== FALSE && ($start < 0 || $end <= 0)) {
            $max = strlen($str);
            if ($start < 0) {
                if (($start = $max + $start) < 0) {
                    return '';
                }
            }
            if ($end < 0) {
                if (($end = $max + $end) < 0) {
                    return '';
                }
            }
            if ($end <= $start) {
                return '';
            }
        }
        $slice = ($end === FALSE) ? substr($str, $start) : substr($str, $start, $end - $start);
        return ($slice === FALSE) ? '' : $slice;
    }
     * Convert strings like "64M" or "30" to int values
     * @param mixed $size
     * @return int
    private function normalize_int($size)
    {
        if (is_string($size)) {
            switch (substr($size, -1)) {
                case 'M': case 'm': return $size * 1048576;
                case 'K': case 'k': return $size * 1024;
                case 'G': case 'g': return $size * 1073741824;
            }
        }
        return (int) $size;
    }
}