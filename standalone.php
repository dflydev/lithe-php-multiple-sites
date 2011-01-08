<?php

define('LITHE_ROOT', dirname(__FILE__));
define('LITHE_LIB_ROOT', LITHE_ROOT . '/lib');
define('LITHE_CONFIG_ROOT', LITHE_ROOT . '/config');
define('LITHE_CONTROLLERS_ROOT', LITHE_ROOT . '/controllers');
define('LITHE_VIEWS_ROOT', LITHE_ROOT . '/views');

require_once(LITHE_ROOT . '/bootstraps/bootstrap.php');

require_once('substrate_Context.php');
require_once('lithe_ContextUtil.php');

// Create the Substrate context.
$context = new substrate_Context(array(
    'lithe_base.context.php',
    'app.context.php',
    'controllers.context.php',
    'standalone.app.context.php', // Use in case of no app specific configuration
));

// Execute the context.
$context->execute();

