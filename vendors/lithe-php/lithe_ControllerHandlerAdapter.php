<?php

require_once('halo_IHandlerAdapter.php');
require_once('lithe_IController.php');
require_once('lithe_Controller.php');
require_once('halo_HttpRequest.php');
require_once('halo_HttpResponse.php');
require_once('halo_ModelAndView.php');

if ( ! function_exists('is_assoc') ) {
    function is_assoc($array) {
        return (is_array($array) && (count($array)==0 || 0 !== count(array_diff_key($array, array_keys(array_keys($array))) )));
    } 
}

class lithe_ControllerHandlerAdapter implements halo_IHandlerAdapter {

    /**
     * (non-PHPdoc)
     * @see halo_IHandlerAdapter::supports()
     */
    public function supports($object) {
        return $object instanceof lithe_IController;
    }

    /**
     * (non-PHPdoc)
     * @see halo_IHandlerAdapter::handle()
     */
    public function handle(halo_HttpRequest $httpRequest, halo_HttpResponse $httpResponse, $handler) {

        $args = array();
        
        $method = lithe_ContextUtil::GET_CONTROLLER_METHOD($httpRequest);
        $uriParams = lithe_ContextUtil::GET_URI_PARAMS($httpRequest);
        
        if ( method_exists($handler, 'setHttpRequestAndResponse') ) {
            $handler->setHttpRequestAndResponse($httpRequest, $httpResponse);
        }
        
        if ( is_assoc($uriParams) ) { $args[] = $uriParams; }
        else { $args = $uriParams; }

        $rv = call_user_func_array(array($handler, $method), $args);
        
        if ( $rv and $rv instanceof halo_ModelAndView ) {
            return $rv;
        }

        if ( $handler instanceof lithe_Controller ) {
            return $handler->generateHaloModelAndView();
        }
        
        return $rv;
        
    }

}
