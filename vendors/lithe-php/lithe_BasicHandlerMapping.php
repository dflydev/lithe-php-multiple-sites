<?php

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

require_once('halo_handler_AbstractHandlerMapping.php');
require_once('halo_HttpRequest.php');
require_once('halo_DispatcherUtil.php');

require_once('substrate_IClassLoader.php');

class lithe_BasicHandlerMapping extends halo_handler_AbstractHandlerMapping {
    
    /**
     * Logger
     * @var dd_logging_ILogger
     */
    static public $LOGGER;
    
    /**
     * Configuration
     * @var dd_configuration_IConfiguration
     */
    protected $configuration;
    
    /**
     * Class loader
     * @var substrate_IClassLoader
     */
    protected $classLoader;
    
    public function __construct(dd_configuration_IConfiguration $configuration, substrate_IClassLoader $classLoader) {
        $this->configuration = $configuration;
        $this->classLoader = $classLoader;
    }
    
    /**
     * Try to find a handler for the request
     * @param halo_HttpRequest $httpRequest
     */
    protected function getHandlerInternal(halo_HttpRequest $httpRequest) {

        $requestedUri = $httpRequest->requestedUri();
        
        if ( ! $requestedUri ) {
            if ( $this->configuration->get('lithe.controllers.defaultHandlerMapping.defaultController') ) {
                $requestedUri = $this->configuration->get('lithe.controllers.defaultHandlerMapping.defaultController');
            } else {
                // We requested the root URI but we have no default
                // controller specified. Clearly we cannot handle
                // this request.
                return null;
            }
        }
        
        if ( self::$LOGGER->isDebugEnabled() ) {
            self::$LOGGER->debug('Checking to see if we can handle requested URI: [' . $requestedUri . ']');
        }
        
        $uriParts = explode('/', $requestedUri);
        $method = null;
        $controller = array_shift($uriParts);

        if ( count($uriParts) > 0 ) {
            $method = array_shift($uriParts);
            if ( $method === '' ) $method = null;
        }

        if ( $method === null ) {
            $method = $this->configuration->get('lithe.controllers.defaultHandlerMapping.defaultMethod');
        }
        
        $controllerClassName =
            $this->configuration->get('lithe.controllers.defaultHandlerMapping.classNamePrefix') .
            ucfirst($controller) .
            $this->configuration->get('lithe.controllers.defaultHandlerMapping.classNameSuffix');
            
        $controllerStoneName =
            ( $this->configuration->get('lithe.controllers.defaultHandlerMapping.stoneNamePrefix') ?
                $this->configuration->get('lithe.controllers.defaultHandlerMapping.stoneNamePrefix') . (
                    preg_match('/[a-zA-Z]$/', $this->configuration->get('lithe.controllers.defaultHandlerMapping.stoneNamePrefix')) ?
                        ucfirst($controller) :
                        lcfirst($controller)
                ) :
                lcfirst($controller)) .
            $this->configuration->get('lithe.controllers.defaultHandlerMapping.stoneNameSuffix');
        
        if ( self::$LOGGER->isDebugEnabled() ) {
            self::$LOGGER->debug('Controller: ' . $controllerClassName . '->' . $method . '(' . implode(', ', $uriParts) . ')');
            self::$LOGGER->debug('Stone: ' . $controllerStoneName);
            self::$LOGGER->debug('URI Params: ' . print_r($uriParts, true));
        }
        
        $context = halo_DispatcherUtil::GET_CONTEXT($httpRequest);
        
        $controllerObject = null;
        
        if ( $context->exists($controllerStoneName) ) {
            if ( self::$LOGGER->isDebugEnabled() ) {
                self::$LOGGER->debug('Found stone for this controller');
            }
            $controllerObject = $context->get($controllerStoneName);
        } elseif ( $this->classLoader->load($controllerClassName) ) {
            if ( self::$LOGGER->isDebugEnabled() ) {
                self::$LOGGER->debug('Found class for this controller');
            }
            $stone = $context->add(array(
                'className' => $controllerClassName,
                'parent' => $this->configuration->get('lithe.controllers.defaultHandlerMapping.defaultParent'),
            ));
            $controllerObject = $context->get($stone);
        }
        
        if ( $controllerObject !== null ) {
            lithe_ContextUtil::SET_URI_PARAMS($httpRequest, $uriParts);
            lithe_ContextUtil::SET_CONTROLLER_METHOD($httpRequest, $method);
        }
        
        return $controllerObject;

    }
    
}

lithe_BasicHandlerMapping::$LOGGER = dd_logging_LogFactory::get('lithe_BasicHandlerMapping');
