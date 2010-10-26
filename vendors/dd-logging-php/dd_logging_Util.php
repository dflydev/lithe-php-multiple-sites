<?php

require_once('dd_logging_ILogger.php');

class dd_logging_Util {
    
    /**
     * Setup error and exception handling
     * @param dd_logging_ILogger $logger
     */
    static public function SET_ERROR_HANDLER(dd_logging_ILogger $logger) {
        set_error_handler(array($logger, 'handleError'));
        set_exception_handler(array($logger, 'handleException'));
    }
    
}

?>
