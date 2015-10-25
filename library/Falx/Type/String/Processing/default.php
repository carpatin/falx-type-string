<?php

use Falx\Type\String\Processing\PluginFactory;

return array(
    PluginFactory::PLUGIN_CASEFOLDING => array(
        array(
            'class' => 'Multibyte',
            'usable' => function () {
                return extension_loaded('mbstring');
            }
        ),
        array(
            'class' => 'Custom',
            'usable' => true
        ),
    ),
    PluginFactory::PLUGIN_COMPARISON => array(
        array(
            'class' => 'Intl',
            'usable' => function () {
                return extension_loaded('intl');
            }
        ),
        array(
            'class' => 'Custom',
            'usable' => true
        )
    ),
    PluginFactory::PLUGIN_EDGING => array(
        array(
            'class' => 'Custom',
            'usable' => true
        )
    ),
    PluginFactory::PLUGIN_LENGTH => array(
        array(
            'class' => 'Multibyte',
            'usable' => function () {
                return extension_loaded('mbstring');
            }
        ),
        array(
            'class' => 'Custom',
            'usable' => true
        )
    ),
    PluginFactory::PLUGIN_LOOKUP => array(
        array(
            'class' => 'Multibyte',
            'usable' => function () {
                return extension_loaded('mbstring');
            }
        )
    )
);

