<?php

interface dd_logging_ILogger {
    
    /**
     * Log a trace message
     * @param $message
     */
    public function trace($message);

    /**
     * Log a trace message
     * @param $message
     */
    public function debug($message);

    /**
     * Log an info message
     * @param $message
     */
    public function info($message);
    
    /**
     * Log a warn message
     * @param $message
     */
    public function warn($message);
    
    /**
     * Log an error message
     * @param $message
     */
    public function error($message);
    
    /**
     * Log a fatal message
     * @param $message
     */
    public function fatal($message);
    
    /**
     * Is trace enabled?
     * @return bool
     */
    public function isTraceEnabled();

    /**
     * Is debug enabled?
     * @return bool
     */
    public function isDebugEnabled();
    
    /**
     * Is info enabled?
     * @return bool
     */
    public function isInfoEnabled();

    /**
     * Is warn enabled?
     * @return bool
     */
    public function isWarnEnabled();

    /**
     * Is error enabled?
     * @return bool
     */
    public function isErrorEnabled();

    /**
     * Is fatal enabled?
     * @return bool
     */
    public function isFatalEnabled();

    
    /**
     * Handle an exception
     * @param $e
     */
    public function handleException(Exception $e);
    
    /**
     * Handle an error
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $errcontext
     */
    public function handleError($errno, $errstr, $errfile, $errline, $errcontext);
    
}

?>
