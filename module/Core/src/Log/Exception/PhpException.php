<?php
namespace Core\Log\Exception;

class PhpException extends \Exception
{
    /**
     * PHP error constants to corresponding name lookup
     *
     * @var array
     */
    protected $_codeNameLookup = array(
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
    );

    /**
     * Constructor
     *
     * @param string $message
     * @param int $code
     * @param string $file
     * @param int $line
     */
    public function __construct($message, $code, $file, $line)
    {
        $message = $message . ' [' . (isset($this->_codeNameLookup[$code]) ? $this->_codeNameLookup[$code] : $code) . ']';

        $this->file = $file;
        $this->line = $line;

        parent::__construct($message, $code);
    }
}