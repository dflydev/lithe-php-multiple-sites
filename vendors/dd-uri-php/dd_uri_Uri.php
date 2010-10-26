<?php

require_once('dd_uri_UriConfiguration.php');

class dd_uri_Uri {
    
    /**
     * URI Configuration
     * @var dd_uri_UriConfiguration
     */
    private $uriConfiguration;
    
    /**
     * Default site base
     * 
     * For relative URI generation
     * @var string
     */
    private $defaultSiteBase;
    
    /**
     * Server environment
     * @var array
     */
    private $env;
    
    /**
     * Constructor
     * @param $uriConfiguration
     * @param $defaultSiteBase
     * @param $alwaysGenerateAbsoluteUris
     */
    public function __construct(dd_uri_UriConfiguration $uriConfiguration = null, $defaultSiteBase = null, $alwaysGenerateAbsoluteUris = null, $env = null) {
        if ( $uriConfiguration === null ) {
            require_once('dd_configuration_MapConfiguration.php');
            $configuration = new dd_configuration_MapConfiguration();
            $uriConfiguration = new dd_uri_UriConfiguration($configuration);
        }
        $this->uriConfiguration = $uriConfiguration;
        $this->defaultSiteBase = $defaultSiteBase;
        $this->alwaysGenerateAbsoluteUris =
            $alwaysGenerateAbsoluteUris === null ? true : $alwaysGenerateAbsoluteUris;
        $this->env = $env === null ? $_SERVER : $env;
    }
    
    /**
     * Resolve a value
     * @param $paramName
     * @param $options
     * @param $existingQueryParams
     * @param $existingPostParams
     * @param $existingUrlParams
     */
    protected function resolveValue($paramName, $options = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $value = null;
        if ( $options !== null and array_key_exists($paramName, $options) ) {
            $value = $options[$paramName];
        } elseif ( $existingUrlParams !== null and array_key_exists($paramName, $existingUrlParams) ) {
            $value = $existingUrlParams[$paramName];
        } elseif ( $existingQueryParams !== null and array_key_exists($paramName, $existingQueryParams) ) {
            $value = $existingQueryParams[$paramName];
        } elseif ( $existingPostParams !== null and array_key_exists($paramName, $existingPostParams) ) {
            $value = $existingPostParams[$paramName];
        }
        return $value;
    }

    /**
     * Get a URI
     * @param unknown_type $path
     * @param unknown_type $options
     * @param unknown_type $remember
     * @param unknown_type $secure
     * @param unknown_type $existingQueryParams
     * @param unknown_type $existingPostParams
     * @param unknown_type $existingUrlParams
     */
    public function get($path, $options = null, $remember = null, $secure = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $args = func_get_args();
        if ( preg_match('/^http[s]{0,1}/', $args[0]) ) {
            return $args[0];
        }
        if ( $this->alwaysGenerateAbsoluteUris ) { $method = 'getAbsolute'; }
        else { $method = 'getRelative'; }
        return call_user_func_array(array($this, $method), $args);
    }

    /**
     * Get a relative URI
     * @param $path
     * @param $options
     * @param $remember
     * @param $secure
     * @param $existingQueryParams
     * @param $existingPostParams
     * @param $existingUrlParams
     */
    public function getRelative($path, $options = null, $remember = null, $secure = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $args = func_get_args();
        array_unshift($args, null);
        return call_user_func_array(array($this, 'getPath'), $args);
    }

    /**
     * Get an absolute URI
     * @param $path
     * @param $options
     * @param $remember
     * @param $secure
     * @param $existingQueryParams
     * @param $existingPostParams
     * @param $existingUrlParams
     */
    public function getAbsolute($path, $options = null, $remember = null, $secure = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $args = func_get_args();
        return $this->getSiteRoot(null, $secure) . call_user_func_array(array($this, 'getRelative'), $args);
    }

    /**
     * Get a site URI
     * @param $site
     * @param $path
     * @param $options
     * @param $remember
     * @param $secure
     * @param $existingQueryParams
     * @param $existingPostParams
     * @param $existingUrlParams
     */
    public function getSite($site, $path, $options = null, $remember = null, $secure = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $args = func_get_args();
        return $this->getSiteRoot($site, $secure) . call_user_func_array(array($this, 'getPath'), $args);
    }

    /**
     * Get a path
     * @param unknown_type $site
     * @param unknown_type $path
     * @param unknown_type $options
     * @param unknown_type $remember
     * @param unknown_type $secure
     * @param unknown_type $existingQueryParams
     * @param unknown_type $existingPostParams
     * @param unknown_type $existingUrlParams
     */
    protected function getPath($site, $path, $options = null, $remember = null, $secure = null, $existingQueryParams = null, $existingPostParams = null, $existingUrlParams = null) {
        $alreadyAdded = array();
        preg_match_all('/:(\w+)/', $path, $matches);
        foreach ( $matches[1] as $replaceCandidate ) {
            $value = $this->resolveValue($replaceCandidate, $options, $existingQueryParams, $existingPostParams, $existingUrlParams);
            if ( $value !== null ) {
                $path = preg_replace("/:$replaceCandidate/", $value, $path);
                $alreadyAdded[$replaceCandidate] = true;
            }
        }
        $path = preg_replace('/^\/+/', '', $path);
        $queryParams = array();
        if ( $options !== null and is_array($options) ) {
            foreach ( $options as $paramName => $value ) {
                if ( ! isset($alreadyAdded[$paramName]) ) {
                    $alreadyAdded[$paramName] = true;
                    $queryParams[$paramName] = $value;
                }
            }
        }
        if ( $remember !== null and is_array($remember) ) {
            foreach ( $remember as $paramName ) {
                if ( ! isset($alreadyAdded[$paramName]) ) {
                    $value = $this->resolveValue($paramName, $options, $existingQueryParams, $existingPostParams, $existingUrlParams);
                    if ( $value !== null ) {
                        $queryParams[$paramName] = $value;
                        $alreadyAdded[$paramName] = true;
                    }
                }
            }
        }

        if ( count($queryParams) ) {
            $queryParamsFinal = array();
            foreach ( $queryParams as $paramName => $value ) {
                $isArray = is_array($value);
                if ( ! $isArray ) {
                    $value = array($value);
                }
                foreach ( $value as $v ) {
                    $queryParamsFinal[] =
                        rawurlencode($paramName) . ( $isArray ? '[]=' : '=' ) .rawurlencode($v);
                }
            }
            if ( count($queryParamsFinal) ) {
                $path .= '?' . implode('&', $queryParamsFinal);
            }
        }

        return $path;
    }

    /**
     * Get a site root
     * @param $site
     * @param $secure
     */
    public function getSiteRoot($site = null, $secure = null) {
        if ( $secure === null ) {
            if ( isset($this->env['HTTPS']) and strtolower($this->env['HTTPS']) == 'on' ) {
                $secure = true;
            } else {
                $secure = false;
            }
        }
        if ( $site === null ) {
            $root = $this->uriConfiguration->getBase($secure);
            if ( $root === null ) {
                return $this->defaultSiteBase;
            }
            return $root;
        } else {
            return $this->uriConfiguration->getSiteBase($site, $secure);
        }
    }

}
