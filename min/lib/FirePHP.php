<?php
class FirePHP {
  const VERSION = '0.2.0';
  const LOG = 'LOG';
  const INFO = 'INFO';
  const WARN = 'WARN';
  const ERROR = 'ERROR';
  public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
  {
   * Logs all exceptions to your firebug console and then stops the script.
   * @param Exception $Exception
   * @throws Exception
  function exceptionHandler($Exception) {
    $this->fb($Exception);
  }
  public function setProcessorUrl($URL)
  {
    $this->setHeader('X-FirePHP-ProcessorURL', $URL);
  }
  public function setRendererUrl($URL)
  {
    $this->setHeader('X-FirePHP-RendererURL', $URL);
  }
  public function group($Name) {
    return $this->fb(null, $Name, FirePHP::GROUP_START);
  }
  public function groupEnd() {
    return $this->fb(null, null, FirePHP::GROUP_END);
  }
  public function log($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::LOG);
  } 
  public function info($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::INFO);
  } 
  public function warn($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::WARN);
  } 
  public function error($Object, $Label=null) {
    return $this->fb($Object, $Label, FirePHP::ERROR);
  } 
  public function dump($Key, $Variable) {
    return $this->fb($Variable, $Key, FirePHP::DUMP);
  }
  public function trace($Label) {
    return $this->fb($Label, FirePHP::TRACE);
  } 
  public function table($Label, $Table) {
    return $this->fb($Table, $Label, FirePHP::TABLE);
  }
  public function detectClientExtension() {
    if(!@preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si',$this->getUserAgent(),$m) ||
       !version_compare($m[1][0],'0.0.6','>=')) {
      return false;
    }
    return true;    
  }
  public function fb($Object) {
    if(!$this->enabled) {
      return false;
    }
    if (headers_sent($filename, $linenum)) {
        throw $this->newException('Headers already sent in '.$filename.' on line '.$linenum.'. Cannot send log data to FirePHP. You must have Output Buffering enabled via ob_start() or output_buffering ini directive.');
    }
    $Type = null;
    $Label = null;
    if(func_num_args()==1) {
    } else
    if(func_num_args()==2) {
      switch(func_get_arg(1)) {
        case self::LOG:
        case self::INFO:
        case self::WARN:
        case self::ERROR:
        case self::DUMP:
        case self::TRACE:
        case self::EXCEPTION:
        case self::TABLE:
        case self::GROUP_START:
        case self::GROUP_END:
          $Type = func_get_arg(1);
          break;
        default:
          $Label = func_get_arg(1);
          break;
      }
    } else
    if(func_num_args()==3) {
      $Type = func_get_arg(2);
      $Label = func_get_arg(1);
    } else {
      throw $this->newException('Wrong number of arguments to fb() function!');
    }
    if(!$this->detectClientExtension()) {
      return false;
    }
    $meta = array();
    $skipFinalObjectEncode = false;
    if($Object instanceof Exception) {
      $meta['file'] = $this->_escapeTraceFile($Object->getFile());
      $meta['line'] = $Object->getLine();
      $trace = $Object->getTrace();
      if($Object instanceof ErrorException
         && isset($trace[0]['function'])
         && $trace[0]['function']=='errorHandler'
         && isset($trace[0]['class'])
         && $trace[0]['class']=='FirePHP') {
        $severity = false;
        switch($Object->getSeverity()) {
          case E_WARNING: $severity = 'E_WARNING'; break;
          case E_NOTICE: $severity = 'E_NOTICE'; break;
          case E_USER_ERROR: $severity = 'E_USER_ERROR'; break;
          case E_USER_WARNING: $severity = 'E_USER_WARNING'; break;
          case E_USER_NOTICE: $severity = 'E_USER_NOTICE'; break;
          case E_STRICT: $severity = 'E_STRICT'; break;
          case E_RECOVERABLE_ERROR: $severity = 'E_RECOVERABLE_ERROR'; break;
          case E_DEPRECATED: $severity = 'E_DEPRECATED'; break;
          case E_USER_DEPRECATED: $severity = 'E_USER_DEPRECATED'; break;
        }
        $Object = array('Class'=>get_class($Object),
                        'Message'=>$severity.': '.$Object->getMessage(),
                        'File'=>$this->_escapeTraceFile($Object->getFile()),
                        'Line'=>$Object->getLine(),
                        'Type'=>'trigger',
                        'Trace'=>$this->_escapeTrace(array_splice($trace,2)));
        $skipFinalObjectEncode = true;
      } else {
        $Object = array('Class'=>get_class($Object),
                        'Message'=>$Object->getMessage(),
                        'File'=>$this->_escapeTraceFile($Object->getFile()),
                        'Line'=>$Object->getLine(),
                        'Type'=>'throw',
                        'Trace'=>$this->_escapeTrace($trace));
        $skipFinalObjectEncode = true;
      }
      $Type = self::EXCEPTION;
    } else
    if($Type==self::TRACE) {
      $trace = debug_backtrace();
      if(!$trace) return false;
      for( $i=0 ; $i<sizeof($trace) ; $i++ ) {
        if(isset($trace[$i]['class'])
           && isset($trace[$i]['file'])
           && ($trace[$i]['class']=='FirePHP'
               || $trace[$i]['class']=='FB')
           && (substr($this->_standardizePath($trace[$i]['file']),-18,18)=='FirePHPCore/fb.php'
               || substr($this->_standardizePath($trace[$i]['file']),-29,29)=='FirePHPCore/FirePHP.class.php')) {
        } else
        if(isset($trace[$i]['class'])
           && isset($trace[$i+1]['file'])
           && $trace[$i]['class']=='FirePHP'
           && substr($this->_standardizePath($trace[$i+1]['file']),-18,18)=='FirePHPCore/fb.php') {
        } else
        if($trace[$i]['function']=='fb'
           || $trace[$i]['function']=='trace'
           || $trace[$i]['function']=='send') {
          $Object = array('Class'=>isset($trace[$i]['class'])?$trace[$i]['class']:'',
                          'Type'=>isset($trace[$i]['type'])?$trace[$i]['type']:'',
                          'Function'=>isset($trace[$i]['function'])?$trace[$i]['function']:'',
                          'Message'=>$trace[$i]['args'][0],
                          'File'=>isset($trace[$i]['file'])?$this->_escapeTraceFile($trace[$i]['file']):'',
                          'Line'=>isset($trace[$i]['line'])?$trace[$i]['line']:'',
                          'Args'=>isset($trace[$i]['args'])?$this->encodeObject($trace[$i]['args']):'',
                          'Trace'=>$this->_escapeTrace(array_splice($trace,$i+1)));
          $skipFinalObjectEncode = true;
          $meta['file'] = isset($trace[$i]['file'])?$this->_escapeTraceFile($trace[$i]['file']):'';
          $meta['line'] = isset($trace[$i]['line'])?$trace[$i]['line']:'';
          break;
        }
      }
    } else
    if($Type==self::TABLE) {
      if(isset($Object[0]) && is_string($Object[0])) {
        $Object[1] = $this->encodeTable($Object[1]);
      } else {
        $Object = $this->encodeTable($Object);
      }
      $skipFinalObjectEncode = true;
    } else {
      if($Type===null) {
        $Type = self::LOG;
      }
    }
    if($this->options['includeLineNumbers']) {
      if(!isset($meta['file']) || !isset($meta['line'])) {
        $trace = debug_backtrace();
        for( $i=0 ; $trace && $i<sizeof($trace) ; $i++ ) {
          if(isset($trace[$i]['class'])
             && isset($trace[$i]['file'])
             && ($trace[$i]['class']=='FirePHP'
                 || $trace[$i]['class']=='FB')
             && (substr($this->_standardizePath($trace[$i]['file']),-18,18)=='FirePHPCore/fb.php'
                 || substr($this->_standardizePath($trace[$i]['file']),-29,29)=='FirePHPCore/FirePHP.class.php')) {
          } else
          if(isset($trace[$i]['class'])
             && isset($trace[$i+1]['file'])
             && $trace[$i]['class']=='FirePHP'
             && substr($this->_standardizePath($trace[$i+1]['file']),-18,18)=='FirePHPCore/fb.php') {
          } else
          if(isset($trace[$i]['file'])
             && substr($this->_standardizePath($trace[$i]['file']),-18,18)=='FirePHPCore/fb.php') {
          } else {
            $meta['file'] = isset($trace[$i]['file'])?$this->_escapeTraceFile($trace[$i]['file']):'';
            $meta['line'] = isset($trace[$i]['line'])?$trace[$i]['line']:'';
            break;
          }
        }      
      }
    } else {
      unset($meta['file']);
      unset($meta['line']);
    }
  	$this->setHeader('X-Wf-Protocol-1','http://meta.wildfirehq.org/Protocol/JsonStream/0.2');
  	$this->setHeader('X-Wf-1-Plugin-1','http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/'.self::VERSION);
    $structure_index = 1;
    if($Type==self::DUMP) {
      $structure_index = 2;
    	$this->setHeader('X-Wf-1-Structure-2','http://meta.firephp.org/Wildfire/Structure/FirePHP/Dump/0.1');
    } else {
    	$this->setHeader('X-Wf-1-Structure-1','http://meta.firephp.org/Wildfire/Structure/FirePHP/FirebugConsole/0.1');
    }
    if($Type==self::DUMP) {
    	$msg = '{"'.$Label.'":'.$this->jsonEncode($Object, $skipFinalObjectEncode).'}';
    } else {
      $msg_meta = array('Type'=>$Type);
      if($Label!==null) {
        $msg_meta['Label'] = $Label;
      }
      if(isset($meta['file'])) {
        $msg_meta['File'] = $meta['file'];
      }
      if(isset($meta['line'])) {
        $msg_meta['Line'] = $meta['line'];
      }
    	$msg = '['.$this->jsonEncode($msg_meta).','.$this->jsonEncode($Object, $skipFinalObjectEncode).']';
    }
    $parts = explode("\n",chunk_split($msg, 5000, "\n"));
    for( $i=0 ; $i<count($parts) ; $i++) {
        $part = $parts[$i];
        if ($part) {
            if(count($parts)>2) {
              $this->setHeader('X-Wf-1-'.$structure_index.'-'.'1-'.$this->messageIndex,
                               (($i==0)?strlen($msg):'')
                               . '|' . $part . '|'
                               . (($i<count($parts)-2)?'\\':''));
            } else {
              $this->setHeader('X-Wf-1-'.$structure_index.'-'.'1-'.$this->messageIndex,
                               strlen($part) . '|' . $part . '|');
            }
            $this->messageIndex++;
            if ($this->messageIndex > 99999) {
                throw new Exception('Maximum number (99,999) of messages reached!');             
            }
        }
    }
  	$this->setHeader('X-Wf-1-Index',$this->messageIndex-1);
    return true;
  }
   * Standardizes path for windows systems.
   * @param string $Path
   * @return string
  protected function _standardizePath($Path) {
    return preg_replace('/\\\\+/','/',$Path);    
  }
   * Escape trace path for windows systems
   * @param array $Trace
   * @return array
  protected function _escapeTrace($Trace) {
    if(!$Trace) return $Trace;
    for( $i=0 ; $i<sizeof($Trace) ; $i++ ) {
      if(isset($Trace[$i]['file'])) {
        $Trace[$i]['file'] = $this->_escapeTraceFile($Trace[$i]['file']);
      }
      if(isset($Trace[$i]['args'])) {
        $Trace[$i]['args'] = $this->encodeObject($Trace[$i]['args']);
      }
    }
    return $Trace;    
  }
   * Escape file information of trace for windows systems
   * @param string $File
   * @return string
  protected function _escapeTraceFile($File) {
    /* Check if we have a windows filepath */
    if(strpos($File,'\\')) {
      $file = preg_replace('/\\\\+/','\\',$File);
      return $file;
    }
    return $File;
  }
  protected function setHeader($Name, $Value) {
    return header($Name.': '.$Value);
  }
  protected function getUserAgent() {
    if(!isset($_SERVER['HTTP_USER_AGENT'])) return false;
    return $_SERVER['HTTP_USER_AGENT'];
  }
  protected function newException($Message) {
    return new Exception($Message);
  }
  private $json_objectStack = array();
  private function json_utf82utf16($utf8)
  {
      if(function_exists('mb_convert_encoding')) {
          return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
      }
      switch(strlen($utf8)) {
          case 1:
              return $utf8;
          case 2:
              return chr(0x07 & (ord($utf8{0}) >> 2))
                   . chr((0xC0 & (ord($utf8{0}) << 6))
                       | (0x3F & ord($utf8{1})));
          case 3:
              return chr((0xF0 & (ord($utf8{0}) << 4))
                       | (0x0F & (ord($utf8{1}) >> 2)))
                   . chr((0xC0 & (ord($utf8{1}) << 6))
                       | (0x7F & ord($utf8{2})));
      }
      return '';
  }
  private function json_encode($var)
  {
    if(is_object($var)) {
      if(in_array($var,$this->json_objectStack)) {
        return '"** Recursion **"';
      }
    }
      switch (gettype($var)) {
          case 'boolean':
              return $var ? 'true' : 'false';
          case 'NULL':
              return 'null';
          case 'integer':
              return (int) $var;
          case 'double':
          case 'float':
              return (float) $var;
          case 'string':
              $ascii = '';
              $strlen_var = strlen($var);
              for ($c = 0; $c < $strlen_var; ++$c) {
                  $ord_var_c = ord($var{$c});
                  switch (true) {
                      case $ord_var_c == 0x08:
                          $ascii .= '\b';
                          break;
                      case $ord_var_c == 0x09:
                          $ascii .= '\t';
                          break;
                      case $ord_var_c == 0x0A:
                          $ascii .= '\n';
                          break;
                      case $ord_var_c == 0x0C:
                          $ascii .= '\f';
                          break;
                      case $ord_var_c == 0x0D:
                          $ascii .= '\r';
                          break;
                      case $ord_var_c == 0x22:
                      case $ord_var_c == 0x2F:
                      case $ord_var_c == 0x5C:
                          $ascii .= '\\'.$var{$c};
                          break;
                      case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                          // characters U-00000000 - U-0000007F (same as ASCII)
                          $ascii .= $var{$c};
                          break;
                      case (($ord_var_c & 0xE0) == 0xC0):
                          // characters U-00000080 - U-000007FF, mask 110XXXXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                          $c += 1;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                      case (($ord_var_c & 0xF0) == 0xE0):
                          // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}));
                          $c += 2;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                      case (($ord_var_c & 0xF8) == 0xF0):
                          // characters U-00010000 - U-001FFFFF, mask 11110XXX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}));
                          $c += 3;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                      case (($ord_var_c & 0xFC) == 0xF8):
                          // characters U-00200000 - U-03FFFFFF, mask 111110XX
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}),
                                       ord($var{$c + 4}));
                          $c += 4;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                      case (($ord_var_c & 0xFE) == 0xFC):
                          // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                          // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                          $char = pack('C*', $ord_var_c,
                                       ord($var{$c + 1}),
                                       ord($var{$c + 2}),
                                       ord($var{$c + 3}),
                                       ord($var{$c + 4}),
                                       ord($var{$c + 5}));
                          $c += 5;
                          $utf16 = $this->json_utf82utf16($char);
                          $ascii .= sprintf('\u%04s', bin2hex($utf16));
                          break;
                  }
              }
              return '"'.$ascii.'"';
          case 'array':
              * As per JSON spec if any array key is not an integer
              * we must treat the the whole array as an object. We
              * also try to catch a sparsely populated associative
              * array with numeric keys here because some JS engines
              * will create an array with empty indexes up to
              * max_index which can cause memory issues and because
              * the keys, which may be relevant, will be remapped
              * otherwise.
              * As per the ECMA and JSON specification an object may
              * have any string as a property. Unfortunately due to
              * a hole in the ECMA specification if the key is a
              * ECMA reserved word or starts with a digit the
              * parameter is only accessible using ECMAScript's
              * bracket notation.
              if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                  $this->json_objectStack[] = $var;
                  $properties = array_map(array($this, 'json_name_value'),
                                          array_keys($var),
                                          array_values($var));
                  array_pop($this->json_objectStack);
                  foreach($properties as $property) {
                      if($property instanceof Exception) {
                          return $property;
                      }
                  }
                  return '{' . join(',', $properties) . '}';
              }
              $this->json_objectStack[] = $var;
              $elements = array_map(array($this, 'json_encode'), $var);
              array_pop($this->json_objectStack);
              foreach($elements as $element) {
                  if($element instanceof Exception) {
                      return $element;
                  }
              }
              return '[' . join(',', $elements) . ']';
          case 'object':
              $vars = self::encodeObject($var);
              $this->json_objectStack[] = $var;
              $properties = array_map(array($this, 'json_name_value'),
                                      array_keys($vars),
                                      array_values($vars));
              array_pop($this->json_objectStack);
              foreach($properties as $property) {
                  if($property instanceof Exception) {
                      return $property;
                  }
              }
              return '{' . join(',', $properties) . '}';
          default:
              return null;
      }
  }
  private function json_name_value($name, $value)
  {
      if($name=='GLOBALS'
         && is_array($value)
         && array_key_exists('GLOBALS',$value)) {
        $value['GLOBALS'] = '** Recursion **';
      }
      $encoded_value = $this->json_encode($value);
      if($encoded_value instanceof Exception) {
          return $encoded_value;
      }
      return $this->json_encode(strval($name)) . ':' . $encoded_value;
  }
}
