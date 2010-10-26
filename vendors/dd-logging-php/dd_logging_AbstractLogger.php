<?php

if ( ! defined('E_STRICT') ) { define('E_STRICT', 2048); }
if ( ! defined('E_RECOVERABLE_ERROR') ) { define('E_RECOVERABLE_ERROR', 4096); }
if ( ! defined('E_DEPRECATED') ) { define('E_DEPRECATED', 8192); }
if ( ! defined('E_USER_DEPRECATED') ) { define('E_USER_DEPRECATED', 16384); }

require_once('dd_logging_ILogger.php');

abstract class dd_logging_AbstractLogger implements dd_logging_ILogger {

    /**
     * Class name for which this logger was instantiated
     * @var unknown_type
     */
    protected $className;
    
    /**
     * Class name for which this logger was instantiated (minified)
     * @var unknown_type
     */
    protected $modifiedClassName;

    /**
     * Constructor
     * @param $className
     */
    public function __construct($className = null) {
        if ( $className === null ) {

            foreach ( debug_backtrace() as $trace ) {
                if ( array_key_exists('class', $trace) ) {
                    $testClass = $trace['class'];
                    $testReflectionClass = new ReflectionClass($testClass);
                    if (
                        $testClass !== __CLASS__ and
                        ( ! $testReflectionClass->implementsInterface('dd_logging_ILogger') ) and
                        $testClass !== 'ReflectionClass' and
                        $testClass !== 'dd_logging_LogFactory'
                    ) {
                        $className = $testClass;
                        break;
                    }
                }
            }


        }
        $this->className = $className;
        $this->modifiedClassName = implode('.', explode('_', $this->className));
    }


    protected static $LEVEL_NAMES = array(
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
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_STRICT => 'E_STRICT',
    );

    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::handleException()
     */
    public function handleException(Exception $e) {
        $this->error($e->getMessage());
        foreach ( $e->getTrace() as $trace ) {
            $this->error($trace);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::handleError()
     */
    public function handleError($errno, $errstr, $errfile, $errline, $errcontext) {

        $message = '[' . self::$LEVEL_NAMES[$errno] . '] ' . $errstr . ' in ' . $errfile . ' at line ' . $errline;

        $die = false;
        $method = null;

        switch($errno) {

            case E_COMPILE_ERROR:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_USER_ERROR:
                $method = 'fatal';
                $die = true;
                break;

            case E_PARSE:

                $method = 'error';
                $die = true;

                break;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_USER_WARNING:

                $method = 'warn';

                break;

            case E_NOTICE:
            case E_USER_NOTICE:

                $method = 'warn';

                break;

            case E_STRICT:

                //$method = 'warn';

                break;


                $method = 'info';

                break;

            case E_DEPRECATED:
            case E_USER_DEPRECATED:
            case E_RECOVERABLE_ERROR:

                $method = 'warn';

                break;

            default:

                $method = 'debug';

                break;

        }

        if ( $method !== null ) {
            $this->$method($message);
        }

        if ( $die ) { die(); }

    }

}

?>
