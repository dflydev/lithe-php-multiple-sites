<?php

require_once('dd_logging_AbstractLogger.php');

class dd_logging_SimpleLogger extends dd_logging_AbstractLogger {

    /**
     * Classname to use for defaults.
     * @var string
     */
    static protected $CLASS_DEFAULT = '__default__';
    
    /**
     * Configuration for level settings for each class.
     * @var array
     */
    static public $CLASS_CONFIGURATION = array(
        '__default__' => array(
            'warn' => true,
            'error' => true,
            'fatal' => true,
        ),
    );
    
    /**
     * Configure the level settings for a specific class.
     * @param $className
     * @param $config
     */
    static public function CONFIGURE($className, $config = null) {
        if ( ! isset(self::$CLASS_CONFIGURATION[$className]) ) {
            self::$CLASS_CONFIGURATION[$className] = array();
        }
        foreach ( $config as $level => $enabled ) {
            if ( $enabled === null ) {
                unset(self::$CLASS_CONFIGURATION[$className][$level]);
            } else {
                self::$CLASS_CONFIGURATION[$className][$level] = $enabled;
            }
        }
    }
    
    /**
     * Configure the default level settings
     * @param $config
     */
    static public function CONFIGURE_DEFAULTS($config = null) {
        self::CONFIGURE(self::$CLASS_DEFAULT, $config);
    }
    
    /**
     * Constructor
     * @param $className
     * @param $config
     */
    public function __construct($className = null, $config = null) {
        parent::__construct($className);
        if ( $config ) {
            self::CONFIGURE($this->className, $config);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::trace()
     */
    public function trace($message) {
        if ( $this->isEnabled('trace') )
            $this->logMessage('trace', $message);
    }

    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::debug()
     */
    public function debug($message) {
        if ( $this->isEnabled('debug') )
            $this->logMessage('debug', $message);
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::info()
     */
    public function info($message) {
        if ( $this->isEnabled('info') )
            $this->logMessage('info', $message);
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::warn()
     */    
    public function warn($message) {
        if ( $this->isEnabled('warn') )
            $this->logMessage('warn', $message);
    }

    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::error()
     */
    public function error($message) {
        if ( $this->isEnabled('error') )
            $this->logMessage('error', $message);
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::fatal()
     */
    public function fatal($message) {
        if ( $this->isEnabled('fatal') )
            $this->logMessage('fatal', $message);
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isTraceEnabled()
     */
    public function isTraceEnabled() {
        return $this->isEnabled('trace');
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isDebugEnabled()
     */
    public function isDebugEnabled() {
        return $this->isEnabled('debug');
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isInfoEnabled()
     */
    public function isInfoEnabled() {
        return $this->isEnabled('info');
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isDebugEnabled()
     */
    public function isWarnEnabled() {
        return $this->isEnabled('warn');
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isDebugEnabled()
     */
    public function isErrorEnabled() {
        return $this->isEnabled('error');
    }
    
    /**
     * (non-PHPdoc)
     * @see dd_logging_ILogger::isFatalEnabled()
     */
    public function isFatalEnabled() {
        return $this->isEnabled('fatal');
    }
    
    /**
     * Is logging enabled for a specific level?
     * @param $level
     * @param $default
     * @return bool
     */
    protected function isEnabled($level) {
        if ( isset(self::$CLASS_CONFIGURATION[$this->className][$level]) )
            return self::$CLASS_CONFIGURATION[$this->className][$level];
        if ( isset(self::$CLASS_CONFIGURATION[self::$CLASS_DEFAULT][$level]) )
            return self::$CLASS_CONFIGURATION[self::$CLASS_DEFAULT][$level];
        return false;
    }
    
    /**
     * Log a message
     * @param $level
     * @param $message
     */
    protected function logMessage($level, $message) {
        error_log($this->modifiedClassName . ' - ' . $level . ': ' . $message);
    }
    
}

?>
