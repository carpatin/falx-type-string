<?php

namespace Falx\Type\String\Processing;

/**
 * String processing plugins factory.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class PluginFactory {

    /**
     * Plugin types
     */
    const PLUGIN_LENGTH = 'Length';
    const PLUGIN_COMPARISON = 'Comparison';
    const PLUGIN_CASING = 'Casing';

    /**
     * Implementation types
     */
    const IMPL_CUSTOM = 'Custom';
    const IMPL_MULTIBYTE = 'Multibyte';
    const IMPL_INTL = 'Intl';

    /**
     * Implementations preferences configuration
     * @var array 
     */
    private $preferences = array(
        self::PLUGIN_LENGTH => array(
            self::IMPL_MULTIBYTE,
            self::IMPL_CUSTOM
        ),
        self::PLUGIN_CASING => array(
            self::IMPL_CUSTOM
        //TODO: add the other impl.
        ),
        self::PLUGIN_COMPARISON => array(
            self::IMPL_INTL,
            self::IMPL_MULTIBYTE,
            self::IMPL_CUSTOM
        ),
    );

    /*
     * Singleton pattern implementation. 
     */

    private function __construct() {
        
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
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Creates and returns plugin instance.
     * @param string $plugin one of the PLUGIN_* constants
     * @return \Falx\Type\String\Processing\PluginInterface
     * @author Dan Homorodean <dan.homorodean@gmail.com
     */
    public function get($plugin) {
        $implementations = $this->getPluginImplementationPreferences($plugin);

        $usedClass = null;
        foreach ($implementations as $implementation) {
            $implementationClass = '\Falx\Type\String\Processing\\' . $plugin . '\\' . $implementation;
            if (class_exists($implementationClass) && $this->hasMetDependencies($implementation)) {
                $usedClass = $implementationClass;
                break;
            }
        }

        if ($usedClass === null) {
            throw new \Exception("No plugin implementation is available for $plugin functionality or available implementations have unmet dependencies.");
        }

        $pluginInstance = new $usedClass();
        return $pluginInstance;
    }

    public function getImplementation($plugin, $implementation) {
        //TODO
    }

    /**
     * Returns plugin implementation preferences
     * @param string $plugin
     * @return array
     * @throws \Exception
     * @author Dan Homorodean <dan.homorodean@gmail.com
     */
    private function getPluginImplementationPreferences($plugin) {
        if (isset($this->preferences[$plugin])) {
            return $this->preferences[$plugin];
        } else {
            throw new \Exception('No preferences found for plugin ' . $plugin);
        }
    }

    /**
     * Basic checking if an implementation has met dependencies.
     * @param string $implementation
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function hasMetDependencies($implementation) {
        $dependenciesOk = false;
        switch ($implementation) {
            case self::IMPL_MULTIBYTE:
                $dependenciesOk = extension_loaded('mbstring');
                break;
            case self::IMPL_INTL:
                $dependenciesOk = extension_loaded('intl');
                break;
            case self::IMPL_CUSTOM:
                $dependenciesOk = true;
                break;
        }
        return $dependenciesOk;
    }

}
