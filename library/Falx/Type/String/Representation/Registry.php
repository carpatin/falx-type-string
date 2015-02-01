<?php

namespace Falx\Type\String\Representation;

/**
 * Strings representations registry
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Registry
{
    /*
     * Supported string representations
     */

    const TYPE_CHARACTER_ARRAY = 'CharacterArray';
    const TYPE_CODEPOINT_ARRAY = 'CodePointArray';

    /**
     * Singleton instance 
     * @var Registry
     */
    private static $instance;

    /**
     * Singleton getter
     * @return Registry
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lacal cache array to store strings representations.
     * @var array
     */
    private $representationCache;

    /**
     * Class constructor
     */
    private function __construct()
    {
        $this->representationCache = array();
    }

    /**
     * Creates or returns from cache (a clone of) the suitable representation for the given string.
     * @param string $string
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public function getRepresentation($string, $type = self::TYPE_CHARACTER_ARRAY)
    {
        if (!$this->hasCache($string, $type)) {
            $representationClass = $this->getRepresentationClass($type);
            $representation = new $representationClass($string);
            $this->addToCache($string, $representation, $type);
        }

        return clone $this->getFromCache($string, $type);
    }

    /**
     * Returns empty representation object.
     * @param string $type
     * @return \Falx\Type\String\Representation\representationClass
     */
    public function getEmpty($type = self::TYPE_CHARACTER_ARRAY)
    {
        $representationClass = $this->getRepresentationClass($type);
        return new $representationClass();
    }

    /**
     * Returns representation class name by representation type.
     * @param string $type
     * @return string
     * @throws \Exception If the class is not loaded/accesible.
     */
    private function getRepresentationClass($type = self::TYPE_CHARACTER_ARRAY)
    {
        $namespace = __NAMESPACE__ . '\\' . 'Type';
        $representationClass = $namespace . '\\' . $type;
        if (!class_exists($representationClass)) {
            throw new \Exception('Unknown representation type ' . $type);
        }
        return $representationClass;
    }

    /**
     * Adds a representation to cache
     * @param string $string
     * @param mixed $representation
     * @param string $type
     */
    private function addToCache($string, $representation, $type)
    {
        if (!isset($this->representationCache[$type])) {
            $this->representationCache[$type] = array();
        }
        $this->representationCache[$type][$string] = $representation;
    }

    /**
     * Checks the cache for representation
     * @param string $string
     * @param string $type
     * @return boolean
     */
    private function hasCache($string, $type)
    {
        if (!isset($this->representationCache[$type])) {
            return false;
        }

        return array_key_exists($string, $this->representationCache[$type]);
    }

    /**
     * Returns cached representation
     * @param string $string
     * @param string $type
     * @return boolean|mixed
     */
    private function getFromCache($string, $type)
    {
        if (!isset($this->representationCache[$type])) {
            return false;
        }

        if (!isset($this->representationCache[$type][$string])) {
            return false;
        }

        return $this->representationCache[$type][$string];
    }

}
