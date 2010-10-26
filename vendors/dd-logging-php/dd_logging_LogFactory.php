<?php

if ( ! defined('DD_LOGGING_DEFAULT_LOGGER') ) {
    if ( array_key_exists('DD_LOGGING_DEFAULT_LOGGER', $_SERVER) ) {
        define('DD_LOGGING_DEFAULT_LOGGER', $_SERVER['DD_LOGGING_DEFAULT_LOGGER']);
    } else {
        require_once('dd_logging_SimpleLogger.php');
        define('DD_LOGGING_DEFAULT_LOGGER', 'dd_logging_SimpleLogger');
    }
}

class dd_logging_LogFactory {

    /**
     * Default logger class name
     * @var string
     */
    static private $CLASS_NAME = DD_LOGGING_DEFAULT_LOGGER;

    /**
     * Constructor
     * @param $className
     */
    public function __construct($className = null) {
        if ( $className !== null ) {
            self::$CLASS_NAME = $className;
        }
    }

    /**
     * Get a logger
     */
    static public function get() {
        $args = func_get_args();
        array_unshift($args, null);
        return call_user_func_array(array('self', 'getPure'), $args);
    }

    /**
     * Get a logger
     * 
     * This is an internal call to get a logger of a specific
     * class.
     * @param $className
     */
    static private function getPure($className = null) {
        if ( $className === null ) {
            $className = self::$CLASS_NAME;
        }
        if ( ! class_exists($className) ) {
            // TODO Find a way to handle this more gracefully?
            // Would hate to write yet anothe resource locator
            // and calss loader but might be the best thing to
            // do...
            require_once($className . '.php');
        }
        $args = func_get_args();
        if ( count($args) > 0 ) { array_shift($args); }
        $reflectionObject = new ReflectionClass($className);
        return $reflectionObject->newInstanceArgs($args);
    }

}

?>
