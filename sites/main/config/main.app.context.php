<?php

$context->add('configuration', array(
    'className' => 'dd_configuration_PropertiesConfiguration',
    'constructorArgs' => array(
        'locations' => array(
            'lithe_base.properties',
            'app.properties',
            'app.site.properties',
            'main.app.properties',
            'main.app.site.properties',
        ),
    ),
));

