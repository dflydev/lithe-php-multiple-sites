<?php

$context->add('placeholderConfigurer', array(
    'className' => 'substrate_DdConfigurationPlaceholderConfigurer',
    'constructorArgs' => array(
        'configuration' => $context->ref('configuration'),
    ),
));

$context->add('logFactory', array(
    'className' => 'dd_logging_LogFactory',
));
