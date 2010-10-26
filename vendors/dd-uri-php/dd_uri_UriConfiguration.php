<?php

require_once('dd_configuration_IConfiguration.php');

class dd_uri_UriConfiguration {

    /**
     * The default prefix key
     * @var string
     */
    protected static $DEFAULT_KEY_PREFIX = 'dd.uri.uriConfiguration.';

    /**
     * The default base suffix key
     * @var string
     */
    protected static $DEFAULT_KEY_BASE_SUFFIX = '.base';

    /**
     * The default secure base suffix
     * Enter description here ...
     * @var unknown_type
     */
    protected static $DEFAULT_KEY_SECURE_BASE_SUFFIX = '.secureBase';

    /**
     * Underlying configuration
     * @var dd_configuration_IConfiguration
     */
    protected $configuration;

    /**
     * Constructor
     * @param $configuration
     */
    public function __construct(dd_configuration_IConfiguration $configuration) {
        $this->configuration = $configuration;
        $this->prefix = self::$DEFAULT_KEY_PREFIX;
        $this->baseSuffix = self::$DEFAULT_KEY_BASE_SUFFIX;
        $this->secureBaseSuffix = self::$DEFAULT_KEY_SECURE_BASE_SUFFIX;
    }

    /**
     * Get the name for the underlying configuration
     * 
     * Convenience method
     * @param string $name
     */
    protected function get($name) {
        return $this->configuration->get($name);
    }
    
    /**
     * See if the name exists int he underlying configuration
     * @param $name
     */
    protected function exists($name) {
        return $this->configuration->exists($name);
    }

    /**
     * Get the base value
     * @param $secure
     */
    public function getBase($secure = null) {
        return $this->getSiteBase($this->get($this->prefix . 'defaultSite'), $secure);
    }

    /**
     * Get the base value for a site
     * @param $site
     * @param $secure
     */
    public function getSiteBase($site, $secure = null) {
        if ( $secure === null ) {
            if ( isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on' ) {
                $secure = true;
            } else {
                $secure = false;
            }
        }
        $baseKey = $this->prefix . $site . $this->baseSuffix;
        $secureBaseKey = $this->prefix . $site . $this->secureBaseSuffix;
        if ( $secure === false and $this->exists($baseKey) ) {
            return $this->get($baseKey);
        } elseif ( $secure === true and $this->exists($secureBaseKey) ) {
            return $this->get($secureBaseKey);
        }
        return null;
    }

}

