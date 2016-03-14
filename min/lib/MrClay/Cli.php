<?php 
namespace MrClay;
use MrClay\Cli\Arg;
use InvalidArgumentException;
    public function getPathArgs()
    {
        $r = $this->moreArgs;
        foreach ($r as $k => $v) {
            if ($v[0] !== '/' && $v[0] !== '~') {
                $v = getcwd() . "/$v";
                $v = str_replace('/./', '/', $v);
                do {
                    $v = preg_replace('@/[^/]+/\\.\\./@', '/', $v, 1, $changed);
                } while ($changed);
                $r[$k] = $v;
            }
        }
        return $r;
    }
    public function getErrorReport()
    {
        if (empty($this->errors)) {
            return '';
        }
        $r = "Some arguments did not pass validation:\n";
        foreach ($this->errors as $letter => $arr) {
            $r .= "  $letter : " . implode(', ', $arr) . "\n";
        }
        $r .= "\n";
        return $r;
    }
    public function getArgumentsListing()
    {
        $r = "\n";
        foreach ($this->_args as $letter => $arg) {
            $desc = $arg->getDescription();
            $flag = " -$letter ";
            if ($arg->mayHaveValue) {
                $flag .= "[VAL]";
            } elseif ($arg->mustHaveValue) {
                $flag .= "VAL";
            }
            if ($arg->assertFile) {
                $flag = str_replace('VAL', 'FILE', $flag);
            } elseif ($arg->assertDir) {
                $flag = str_replace('VAL', 'DIR', $flag);
            }
            if ($arg->isRequired()) {
                $desc = "(required) $desc";
            }
            $flag = str_pad($flag, 12, " ", STR_PAD_RIGHT);
            $desc = wordwrap($desc, 70);
            $r .= $flag . str_replace("\n", "\n            ", $desc) . "\n\n";
        }
        return $r;
    }
    public function openInput()
    {
        if (null === $this->_stdin) {
            return STDIN;
        } else {
            $this->_stdin = fopen($this->_stdin, 'rb');
            return $this->_stdin;
        }
    }
    public function closeInput()
    {
        if (null !== $this->_stdin) {
            fclose($this->_stdin);
        }
    }
    public function openOutput()
    {
        if (null === $this->_stdout) {
            return STDOUT;
        } else {
            $this->_stdout = fopen($this->_stdout, 'wb');
            return $this->_stdout;
        }
    }
    public function closeOutput()
    {
        if (null !== $this->_stdout) {
            fclose($this->_stdout);
        }
    }
    protected function addError($letter, $msg, $value = null)
    {
        if ($value !== null) {
            $value = var_export($value, 1);
        }
        $this->errors[$letter][] = sprintf($msg, $value);
    }
}
