<?php

require_once('dd_core_IClassLoader.php');

abstract class dd_core_AbstractClassLoader implements dd_core_IClassLoader {

    /**
     * Simple class template
     * @todo Figure out a better way to handle this
     * @param $className
     */
    static protected function SIMPLE_CLASS_TEMPLATE($className) {
        return $className . '.php';
    }
    
    /**
     * Class template callbacks
     * @var array
     */
    private $templateCallbacks;
    
    /**
     * Constructor
     * @param $templateCallbacks
     */
    public function __construct($templateCallbacks = null) {
        if ( $templateCallbacks !== null ) {
            $this->templateCallbacks = $templateCallbacks;
        } else {
            $this->templateCallbacks = array(
                // TODO Figure out a better way to handle this
                array('dd_core_AbstractClassLoader', 'SIMPLE_CLASS_TEMPLATE'),
            );
        }
    }
    
    /**
     * Load a class
     * @param $className
     * @param $includeFilename
     */
    public function load($className, $includeFilename = null) {
        if ( class_exists($className) ) return true;
        if ( isset($this->loadAttemptedLocally[$className]) ) return false;
        $this->loadAttemptedLocally[$className] = true;
        if ( $includeFilename !== null ) {
            // If an include filename was specified, we'll
            // just assume that is what we are looking for.
            require_once($includeFilename);
            return true;
        }
        foreach ( $this->potentialIncludeFilenames($className) as $includeFilename ) {
            if ( $fullIncludePath = $this->find($includeFilename) ) {
                require_once($fullIncludePath);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Determine potential include filenames for the specified class name
     * @param $className
     */
    public function potentialIncludeFilenames($className) {
        $potentialIncludeFilenames = array();
        foreach ( $this->templateCallbacks as $callback ) {
            $potentialIncludeFilenames[] = call_user_func($callback, $className);
        }
        return $potentialIncludeFilenames;
    }
    
    /**
     * Leave the finding of the class filename up to subclasses
     * @param $classFileName
     */
    abstract public function find($classFileName);

}
