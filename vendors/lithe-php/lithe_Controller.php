<?php

require_once('dd_logging_LogFactory.php');
require_once('dd_logging_ILogger.php');

require_once('lithe_IController.php');
require_once('lithe_Model.php');

require_once('substrate_Context.php');
require_once('substrate_stones_IContextAware.php');

require_once('halo_ModelAndView.php');

require_once('halo_HelperUtil.php');

class lithe_Controller implements lithe_IController, substrate_stones_IContextAware {
    
    /**
     * Redirect URI
     * @var string
     */
    protected $redirect = null;
    
    /**
     * Default view
     * Enter description here ...
     * @var string
     */
    protected $defaultView = 'default';
    
    /**
     * Name of selected view
     * @var string
     */
    protected $view;
    
    /**
     * Model data
     * Enter description here ...
     * @var lithe_Model
     */
    protected $model;
    
    /**
     * Logger
     * @var dd_logging_ILogger
     */
    static public $LOGGER;

    /**
     * Substrate context
     * @var substrate_Context
     */
    protected $context;
    
    /**
     * HTTP Request
     * @var halo_HttpRequest
     */
    protected $httpRequest;
    
    /**
     * HTTP Response
     * @var halo_HttpResponse
     */
    protected $httpResponse;
    
    /**
     * Constructor
     */
    public function __construct() {
        if ( self::$LOGGER->isDebugEnabled() ) {
            self::$LOGGER->debug('In constructor.');
        }
        $this->model = $this->data = new lithe_Model();
    }
    
    /**
     * Inform controller about Substrate context
     * @param $context
     */
    public function informAboutContext(substrate_Context $context) {
        if ( self::$LOGGER->isDebugEnabled() ) {
            self::$LOGGER->debug('Informed of Substrate context startup.');
        }
        $this->context = $context;
    }
    
    /**
     * Generate Halo's Model and View
     * @return halo_ModelAndView
     */
    public function generateHaloModelAndView() {
        if ( $this->redirect ) {
            header('Location: ' . $this->redirect);
            return null;
        }
        $this->model->set('baseUri', $this->httpRequest->scriptPathRoot());
        return new halo_ModelAndView($this->view ? $this->view : $this->defaultView, $this->model->export());
    }

    /**
     * Set Halo's HTTP Request and HTTP Response objects
     * @param $httpRequest
     * @param $httpResponse
     */
    public function setHttpRequestAndResponse(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse) {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
    }
    
    public function helper($name) {
        halo_HelperUtil::REGISTER_HELPER_NAME($this->httpRequest, $name);
        return halo_HelperUtil::MANAGER(
            $this->httpRequest,
            $this->httpResponse
        )->helper($name);
    }
    
}

lithe_Controller::$LOGGER = dd_logging_LogFactory::get('lithe_Controller');
