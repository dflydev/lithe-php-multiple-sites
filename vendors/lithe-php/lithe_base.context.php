<?php

$context->import('halo_base.context.php');

/**
 * Lithe dispatcher
 * 
 * The Lithe dispatcher is (currently) nothing special.
 * 
 * It just masks Halo so "Lithe" users can be blissfully
 * unaware of Halo if they choose to be.
 */
$context->add('lithe.dispatcher', array(
    'parent' => 'halo.dispatcher',
));

/**
 * Lithe controller handler adapter
 */
$context->add('lithe.handlers.controllerHandlerAdapter', array(
    'className' => 'lithe_ControllerHandlerAdapter',
));

/**
 * Class loader for Lithe controllers
 */
$context->add('lithe.controllers.classLoader', array(
    'className' => 'substrate_ResourceLocatorClassLoader',
    'constructorArgs' => array(
        'resourceLocator' => $context->ref('lithe.controllers.resourceLocator'),
    ),
));

/**
 * Default handler mapping
 */
$context->add('lithe.handlers.defaultHandlerMapping', array(
    'className' => 'lithe_BasicHandlerMapping',
    'constructorArgs' => array(
        'configuration' => $context->ref('lithe.controllers.configuration'),
        'classLoader' => $context->ref('lithe.controllers.classLoader'),
    ),
    'dependencies' => array(
        'lithe.handlers.controllerHandlerAdapter',
    ),
));

/**
 * Default parent controller
 */
$context->add('lithe.controllers.defaultParentController', array(
    'className' => 'lithe_AbstractEmptyController',
    'abstract' => true,
));

/**
 * Lithe default view resolver
 */
$context->add('lithe.views.defaultViewResolver', array(
    'className' => 'halo_view_ViewFactoryResourceViewResolver',
    'constructorArgs' => array(
        'viewFactory' => $context->ref('${lithe.views.default.viewFactoryStone}'),
    ),
    'properties' => array(
        'suffix' => '${lithe.views.default.suffix}',
    ),
));

/**
 * Skittle view factory
 */
$context->add('lithe.views.skittle.viewFactory', array(
    'className' => 'halo_view_skittle_SkittleViewFactory',
    'constructorArgs' => array(
        'resourceLocator' => $context->ref('lithe.views.resourceLocator'),
    ),
));

/**
 * Configuration Helper Factory
 * 
 * This gets us a URI helper that we can use to generate absolute
 * and cross site URIs.
 */
$context->add('lithe.helpers.configurationHelperFactory', array(
    'className' => 'halo_helper_ConfigurationHelperFactory',
    'constructorArgs' => array(
        'configuration' => $context->ref('configuration'),
    ),
    'lazyLoad' => false,
));

/**
 * URI Helper Factory
 * 
 * This gets us a URI helper that we can use to generate absolute
 * and cross site URIs.
 */
$context->add('lithe.helpers.uriHelperFactory', array(
    'className' => 'halo_helper_UriHelperFactory',
    'constructorArgs' => array(
        'uriConfiguration' => $context->ref('lithe.uriConfiguration'),
    ),
    'lazyLoad' => false,
));

/**
 * URI Configuration
 * 
 * We default to our app configuration instance.
 */
$context->set('lithe.uriConfiguration', array(
    'className' => 'dd_uri_UriConfiguration',
    'constructorArgs' => array(
        'configuration' => $context->ref('configuration')
    ),
));
