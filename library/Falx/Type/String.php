<?php

namespace Falx\Type;

use Falx\Type\String\Processing\PluginFactory;
use Falx\Type\String\Processing\Plugin;

/**
 * String class
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class String {

    /**
     * PHP string representation (default used representation)
     * @var string
     */
    private $string;

    /**
     * Constructor
     * @param \Falx\Type\String|string $string
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function __construct($string = '') {

        if (is_string($string)) {
            $this->string = $string;
        } elseif ($string instanceof String) {
            $this->string = $string->string;
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is neither a String nor a string literal');
        }
    }

    /**
     * Creates and returns a copy of the current String instance
     * @return \Falx\Type\String
     */
    public function copy() {
        $copy = new self();
        $copy->string = $this->string;
        return $copy;
    }

    /**
     * Returns the wrapped string literal
     * @return string
     */
    public function literal() {
        return $this->string;
    }

    /**
     * Implementation of the magic method
     * @return string
     */
    public function __toString() {
        return $this->string;
    }

    /* ======================== */
    /* String class operations  */
    /* ======================== */

    /**
     * Returns plugins factory instance, for internal use.
     * @return \Falx\Type\String\Processing\PluginFactory
     */
    private function plugins() {
        return PluginFactory::getInstance();
    }

    /*
     * Length
     */

    /**
     * Returns the length of the string
     * @return int
     */
    function length() {
        /* @var $plugin Plugin\Length */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_LENGTH);
        return $plugin->length($this);
    }

    /*
     * Comparison
     */

    /**
     * Compares this string to another.
     * @param \Falx\Type\String $another
     * @return int
     */
    public function compareTo(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->compareTo($this, $another);
    }

    /**
     * Returns true if the string equals another, false if it doesn't.
     * @param \Falx\Type\String $another
     * @return boolean
     */
    public function equals(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->equals($this, $another);
    }

    /**
     * Returns true if the string equals another, false if it doesn't.
     * Letters case is ignored when comparing.
     * @param \Falx\Type\String $another
     * @return boolean
     */
    public function equalsIgnoringCase(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->equalsIgnoringCase($this, $another);
    }

}
