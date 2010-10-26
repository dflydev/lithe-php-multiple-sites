<?php

require_once('substrate_Context.php');

class lithe_ContextUtil {
    
    /**
     * Key by which we can set and get our URI params for a given request
     * @var string
     */
    protected static $URI_PARAMS_CONTEXT_KEY = '__lithe_uri_params';
    
    /**
     * Key by which we can set and get our controller method for a given request
     * @var string
     */
    protected static $CONTROLLER_METHOD_CONTEXT_KEY = '__lithe_controller_method';
        
    /**
     * Configure locators
     * @param substrate_Context $context
     * @param array $config
     */
    static public function CONFIGURE_LOCATOR_PATHS(substrate_Context $context, $config) {
        
        if ( isset($config['controllers']) ) {
            $context->add('lithe.controllers.resourceLocator', array(
                'className' => 'substrate_PathResourceLocator',
                'constructorArgs' => array(
                    'paths' => $config['controllers'],
                ),
            ));
        }
        
        if ( isset($config['views']) ) {
            $context->add('lithe.views.resourceLocator', array(
                'className' => 'substrate_PathResourceLocator',
                'constructorArgs' => array(
                    'paths' => $config['views'],
                ),
            ));
        }
        
    }
    
    /**
     * Configure controller configuration
     * @param substrate_Context $context
     * @param array $paths
     */
    static public function CONFIGURE_CONTROLLER_CONFIGURATION(substrate_Context $context, $paths) {
        $context->add('lithe.controllers.configuration', array(
            'className' => 'dd_configuration_PropertiesConfiguration',
            'constructorArgs' => array( 'locations' => $paths, ),
        ));
    }

    /**
     * Configure dependencies for the dispatcher
     * 
     * Used to specify which stones should be initialized prior to the
     * dispatcher executing.
     * 
     * @param substrate_Context $context
     * @param array $dependencies
     */
    static public function CONFIGURE_DISPATCHER_DEPENDENCIES(substrate_Context $context, $dependencies = null) {

        if ( $dependencies and is_array($dependencies) ) {
            foreach ( $dependencies as $stoneName ) {
                $context->get($stoneName);
            }
        }
        
    }
    
    /**
     * Execute the dispatcher
     * @param substrate_Context $context
     * @param array $dependencies
     */
    static public function DISPATCH(substrate_Context $context, $dependencies = null) {

        // Get the Lithe dispatcher
        $dispatcher = $context->get('lithe.dispatcher');
        
        // Do the service.
        $dispatcher->doService(
            halo_DispatcherUtil::MAKE_HTTP_REQUEST($context),
            halo_DispatcherUtil::MAKE_HTTP_RESPONSE()
        );

    }
    
    /**
     * Get the URI params from an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @return substrate_Context
     */
    public static function GET_URI_PARAMS(halo_HttpRequest $httpRequest) {
        return $httpRequest->attribute(self::$URI_PARAMS_CONTEXT_KEY);
    }
    
    /**
     * Set the URI params for an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @param array
     */
    public static function SET_URI_PARAMS(halo_HttpRequest $httpRequest, $uriParams) {
        return $httpRequest->setAttribute(self::$URI_PARAMS_CONTEXT_KEY, $uriParams);
    }
    
    /**
     * Get the controller method from an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @return substrate_Context
     */
    public static function GET_CONTROLLER_METHOD(halo_HttpRequest $httpRequest) {
        return $httpRequest->attribute(self::$CONTROLLER_METHOD_CONTEXT_KEY);
    }
    
    /**
     * Set the controller method for an HTTP Request.
     * @param halo_HttpRequest $httpRequest
     * @param string
     */
    public static function SET_CONTROLLER_METHOD(halo_HttpRequest $httpRequest, $method) {
        return $httpRequest->setAttribute(self::$CONTROLLER_METHOD_CONTEXT_KEY, $method);
    }
    
}
