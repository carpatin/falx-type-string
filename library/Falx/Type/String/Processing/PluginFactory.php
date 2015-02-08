<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing;

use Falx\Type\String\Processing\Plugin\Base as PluginBase;
use Falx\Type\String\Processing\Plugin\Exception as PluginException;

/**
 * String processing plugins factory.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 * @todo Add a cache of created plugins as an optimization
 */
class PluginFactory
{

    /**
     * Plugin types
     */
    const PLUGIN_LENGTH = 'Length';
    const PLUGIN_COMPARISON = 'Comparison';
    const PLUGIN_CASEFOLDING = 'CaseFolding';
    const PLUGIN_EDGING = 'Edging';

    /**
     * Implementation types
     */
    const IMPL_CUSTOM = 'Custom';
    const IMPL_MULTIBYTE = 'Multibyte';
    const IMPL_INTL = 'Intl';
    const IMPL_ICONV = 'Iconv';

    /**
     * Implementations preferences configuration
     * @todo Make this array configurable from outside this class
     * @var array 
     */
    private $preferences = array(
        self::PLUGIN_LENGTH => array(
            self::IMPL_MULTIBYTE, // 1
            self::IMPL_CUSTOM, // 2 
        ),
        self::PLUGIN_CASEFOLDING => array(
            self::IMPL_CUSTOM, // 1
        //TODO: add the other impl.
        ),
        self::PLUGIN_COMPARISON => array(
            self::IMPL_INTL, // 1
            self::IMPL_CUSTOM, // 2
        ),
        self::PLUGIN_EDGING => array(
            self::IMPL_CUSTOM, // 1
        //TODO: add the other impl.
        )
    );

    /*
     * Singleton pattern implementation. 
     */

    private function __construct()
    {
        // Empty
    }

    /**
     * Singșeton instance
     * @var PluginFactory 
     */
    private static $instance;

    /**
     * Returns singleton instance
     * @return PluginFactory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Creates and returns plugin instance.
     * @param string $plugin one of the PLUGIN_* constants
     * @return PluginBase
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function get($plugin)
    {
        $implementationSuffixes = $this->getPluginImplementationPreferences($plugin);

        /* @var $top PluginBase */
        $top = null;
        /* @var $previous PluginBase */
        $previous = null;
        /* @var $current PluginBase */
        $current = null;


        // Build the chain of responsibility from plugins instances
        foreach ($implementationSuffixes as $implementationSuffix) {
            $implementationClass = '\Falx\Type\String\Processing\Plugin\\' . $plugin . '\\' . $implementationSuffix;

            if (class_exists($implementationClass) && $this->hasMetDependencies($implementationSuffix)) {
                $previous = $current;

                $current = new $implementationClass();
                if ($top === null) {
                    $top = $current;
                }

                if ($previous !== null) {
                    $previous->setNext($current);
                }
            }
        }

        if ($top === null) {
            throw new PluginException("No plugin implementation is available for $plugin functionality or available implementations have unmet dependencies.");
        }
var_dump($top);
        
        return $top;
    }

    /**
     * Returns plugin implementation preferences
     * @param string $plugin
     * @return array
     * @throws \Exception
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function getPluginImplementationPreferences($plugin)
    {
        if (isset($this->preferences[$plugin])) {
            return $this->preferences[$plugin];
        } else {
            throw new PluginException('No preferences found for plugin ' . $plugin);
        }
    }

    /**
     * Basic checking if an implementation has met dependencies.
     * @param string $implementation
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function hasMetDependencies($implementation)
    {
        $dependenciesOk = false;
        switch ($implementation) {
            case self::IMPL_MULTIBYTE:
                $dependenciesOk = extension_loaded('mbstring');
                break;
            case self::IMPL_INTL:
                $dependenciesOk = extension_loaded('intl');
                break;
            case self::IMPL_ICONV:
                $dependenciesOk = extension_loaded('iconv');
                break;
            case self::IMPL_CUSTOM:
                $dependenciesOk = true;
                break;
        }
        return $dependenciesOk;
    }

}
