<?php

$context->add('configuration', array(
    'className' => 'dd_configuration_PropertiesConfiguration',
    'constructorArgs' => array(
        'locations' => array(
            'lithe_base.properties',
            'app.properties',
            'app.site.properties',
        ),
    ),
));

$context->add('placeholderConfigurer', array(
    'className' => 'substrate_DdConfigurationPlaceholderConfigurer',
    'constructorArgs' => array(
        'configuration' => $context->ref('configuration'),
    ),
));

$context->add('logFactory', array(
    'className' => 'dd_logging_LogFactory',
));
