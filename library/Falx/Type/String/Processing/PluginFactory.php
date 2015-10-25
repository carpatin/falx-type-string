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
 */
class PluginFactory {

    /**
     * Default plugin types
     */
    const PLUGIN_LENGTH = 'Length';
    const PLUGIN_COMPARISON = 'Comparison';
    const PLUGIN_CASEFOLDING = 'CaseFolding';
    const PLUGIN_EDGING = 'Edging';
    const PLUGIN_LOOKUP = 'Lookup';

    /**
     * A static array property that ciontains all available/provided plugin types
     * @var array 
     */
    private static $availablePluginTypes = array(
        self::PLUGIN_CASEFOLDING,
        self::PLUGIN_COMPARISON,
        self::PLUGIN_EDGING,
        self::PLUGIN_LENGTH,
        self::PLUGIN_LOOKUP,
    );

    /**
     * Allows the user to add a custom plugin type to the factory.
     * @param string $pluginType
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public static function addPluginType($pluginType) {
        self::$availablePluginTypes[] = $pluginType;
    }

    /**
     * The path to the default configuration file for the factory.
     * The factory reads this file from constructor and builds its initial 
     * plugin preferences configuration.
     * @var string
     */
    private static $defaultConfigFilepath = 'default.php';

    /**
     * Static getter for default configuration filepath.
     * @return string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    static function getDefaultConfigFilepath() {
        return self::$defaultConfigFilepath;
    }

    /**
     * Static setter for default configuration filepath.
     * @param string $defaultConfigFilepath
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    static function setDefaultConfigFilepath($defaultConfigFilepath) {
        self::$defaultConfigFilepath = $defaultConfigFilepath;
    }

    /*
     * Singleton pattern implementation. 
     */

    private function __construct() {
        // Load default configuration
        $this->configuration = require self::$defaultConfigFilepath;
        // Initialize empty plugin instances array
        $this->plugins = array();
    }

    /**
     * Singleton instance
     * @var PluginFactory 
     */
    private static $instance;

    /**
     * Returns singleton instance
     * @return PluginFactory
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Implementations preferences configuration
     * @var array 
     */
    private $configuration;

    /**
     * An array with instantiated plguind indexed by their type
     * @var array
     */
    private $plugins;

    /**
     * Appends custom plugin implementation to the list of preferred implementations.
     * Users can use this method to add their own plugin implementations.
     * @param string $pluginType
     * @param array $implementationConfig
     * @throws PluginException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function appendPluginImplementation($pluginType, array $implementationConfig) {
        if (!in_array($pluginType, self::$availablePluginTypes)) {
            throw new PluginException(sprintf('Plugin type %s is unknown.', $pluginType));
        }

        if (!array_key_exists($pluginType, $this->configuration)) {
            $this->configuration[$pluginType] = array();
        }

        array_push($this->configuration[$pluginType], $implementationConfig);
    }

    /**
     * Prepends custom plugin implementation to the list of preferred implementations.
     * Users can use this method to add their own plugin implementations.
     * @param string $pluginType
     * @param array $implementationConfig
     * @throws PluginException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function prependPluginImplementation($pluginType, array $implementationConfig) {
        if (!in_array($pluginType, self::$availablePluginTypes)) {
            throw new PluginException(sprintf('Plugin type %s is unknown.', $pluginType));
        }

        if (!array_key_exists($pluginType, $this->configuration)) {
            $this->configuration[$pluginType] = array();
        }

        array_unshift($this->configuration[$pluginType], $implementationConfig);
    }

    /**
     * Returns the names of the classes that are available as the implementations for the given plugin type.
     * Keeps the order of preference from the configureation.
     * @param string $pluginType
     * @return array An array of class names of implementations for the plugin. 
     * The order of them in array reflects preferences.
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function getPluginTypeImplementations($pluginType) {
        if (!in_array($pluginType, self::$availablePluginTypes)) {
            throw new PluginException(sprintf('Plugin type %s is unknown.', $pluginType));
        }

        $implementations = $this->configuration[$pluginType];
        $i = 0;
        $c = count($implementations);

        if ($c == 0) {
            throw new PluginException(sprintf('Plugin type %s has no implementations configured.', $pluginType));
        }

        $preferedAndAvailable = array();
        do {
            $current = $implementations[$i];
            $usable = $current['usable'];
            if ($usable === true || (is_callable($usable) && call_user_func($usable) === true)) {
                $preferedAndAvailable[] = $current['class'];
            }
            $i++;
        } while ($i < $c);

        return $preferedAndAvailable;
    }

    /**
     * Creates (or returns from array of cached instances) and returns plugin instance.
     * @param string $pluginType one of the PLUGIN_* constants
     * @return PluginBase
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function get($pluginType) {

        if (!isset($this->plugins[$pluginType])) {
            $availableImplementations = $this->getPluginTypeImplementations($pluginType);

            /* @var $first PluginBase */
            $first = null;
            /* @var $previous PluginBase */
            $previous = null;
            /* @var $current PluginBase */
            $current = null;


            // Build the chain of responsibility from plugins instances
            foreach ($availableImplementations as $className) {

                if (!class_exists($className)) {
                    $className = '\Falx\Type\String\Processing\Plugin\\' . $pluginType . '\\' . $className;
                }

                if (!class_exists($className)) {
                    continue;
                }

                $previous = $current;

                $current = new $className();
                if ($first === null) {
                    $first = $current;
                }

                if ($previous !== null) {
                    $previous->setNext($current);
                }
            }

            if ($first === null) {
                throw new PluginException(sprintf('No plugin implementation is available for %s functionality or all implementations have unmet dependencies.', $pluginType));
            }

            $this->plugins[$pluginType] = $first;
        }

        return $this->plugins[$pluginType];
    }

}
