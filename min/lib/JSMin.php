<?php
class JSMin {
    const ORD_LF            = 10;
    const ORD_SPACE         = 32;
    const ACTION_KEEP_A     = 1;
    const ACTION_DELETE_A   = 2;
    const ACTION_DELETE_A_B = 3;
    protected $a           = "\n";
    protected $b           = '';
    protected $input       = '';
    protected $inputIndex  = 0;
    protected $inputLength = 0;
    protected $lookAhead   = null;
    protected $output      = '';
    protected $lastByteOut  = '';
    protected $keptComment = '';
    public static function minify($js)
    {
        $jsmin = new JSMin($js);
        return $jsmin->min();
    }
    public function __construct($input)
    {
        $this->input = $input;
    }
    public function min()
    {
        if ($this->output !== '') { 
            return $this->output;
        }
        $mbIntEnc = null;
        if (function_exists('mb_strlen') && ((int)ini_get('mbstring.func_overload') & 2)) {
            $mbIntEnc = mb_internal_encoding();
            mb_internal_encoding('8bit');
        }
        $this->input = str_replace("\r\n", "\n", $this->input);
        $this->inputLength = strlen($this->input);
        $this->action(self::ACTION_DELETE_A_B);
        while ($this->a !== null) {
            $command = self::ACTION_KEEP_A; 
            if ($this->a === ' ') {
                if (($this->lastByteOut === '+' || $this->lastByteOut === '-')
                        && ($this->b === $this->lastByteOut)) {
                    break;
                case '*':
                    $this->consumeMultipleLineComment();
                    $get = ' ';
                    break;
            }
        }
        return $get;
    }
}
class JSMin_UnterminatedStringException extends Exception {}
class JSMin_UnterminatedCommentException extends Exception {}
class JSMin_UnterminatedRegExpException extends Exception {}
