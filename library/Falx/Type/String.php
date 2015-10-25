<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type;

use Falx\Type\String\Processing\PluginFactory;
use Falx\Type\String\Processing\Plugin;
use Falx\Exception\IllegalArgumentException;

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
     * @throws IllegalArgumentException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function __construct($string = '') {

        if (is_string($string)) {
            $this->string = $string;
        } elseif ($string instanceof String) {
            $this->string = $string->string;
        } else {
            throw new IllegalArgumentException('Argument is neither a String nor a string literal');
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
    /* Mixin functions feature  */
    /* ======================== */

    /**
     * Static array with registered callables.
     * This serves as an extension mechanism to the String class that is manageable and transparent. 
     * @var array
     */
    private static $mixinFunctions = array();

    /**
     * Registers a mixin function.
     * @param string $name
     * @param callable $callable
     * @throws \Exception If the second parameter is not a callable
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public static function registerMixinFunction($name, $callable) {
        if (!is_callable($callable)) {
            throw new \Exception('Expected a callable as second argument. Not a callable given.');
        }
        self::$mixinFunctions[$name] = $callable;
    }

    /**
     * Checks for registered mixin function.
     * @param string $name
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public static function hasMixinFunction($name) {
        return isset(self::$mixinFunctions[$name]);
    }

    /**
     * Magic call method checks for a mixin function registered with the method name 
     * called upon the string instance, and forwards call if founds a registered function.
     * It also passes the $this instance as first argument of call so that the callable has access to 
     * string instance properties and functions.
     * @param string $name The name of the method called.
     * @param array $arguments The array of call arguments.
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        if (!array_key_exists($name, self::$mixinFunctions)) {
            throw new \Exception(sprintf('Mixin function %s not found.', $name));
        }
        array_unshift($arguments, $this);
        $result = call_user_func_array(self::$mixinFunctions[$name], $arguments);
        return $result;
    }

    /* ======================== */
    /* String class operations  */
    /* ======================== */

    /**
     * Returns plugins factory instance, for internal use.
     * @return \Falx\Type\String\Processing\PluginFactory
     * @author Dan Homorodean <dan.homorodean@gmail.com>
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
     * @author Dan Homorodean <dan.homorodean@gmail.com>
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
     * @param String $another
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function compareTo(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->compareTo($this, $another);
    }

    /**
     * Returns true if the string equals another, false if it doesn't.
     * @param String $another
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equals(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->equals($this, $another);
    }

    /**
     * Returns true if the string equals another, false if it doesn't.
     * Letters case is ignored when comparing.
     * @param String $another
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equalsIgnoringCase(String $another) {
        /* @var $plugin Plugin\Comparison */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_COMPARISON);
        return $plugin->equalsIgnoringCase($this, $another);
    }

    /*
     * Casing
     */

    /**
     * Returns uppercase version of the string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toUppercase() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->toUppercase($this);
    }

    /**
     * Returns lowercase version of the string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toLowercase() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->toLowercase($this);
    }

    /**
     * Lowercases first $count letters from the string returning the resulted string.
     * @param int $count
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lowercaseFirst($count = 1) {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->lowercaseFirst($this, $count);
    }

    /**
     * Uppercases first $count letters from the string returning the resulted string.
     * @param int $count
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function uppercaseFirst($count = 1) {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->uppercaseFirst($this, $count);
    }

    /**
     * Lowercases first letter in all words. Returns the resulted string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lowercaseWords() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->lowercaseWords($this);
    }

    /**
     * Uppercases first letter in all words. Returns the resulted string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function uppercaseWords() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->uppercaseWords($this);
    }

    /**
     * Transforms from camel case notation to underscore notation. Returns resulted string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function camelCaseToUnderscore() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->camelCaseToUnderscore($this);
    }

    /**
     * Transforms from underscore notation to camel case notation. Returns resulted string.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function undescoreToCamelCase() {
        /* @var $plugin Plugin\Casing */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_CASEFOLDING);
        return $plugin->undescoreToCamelCase($this);
    }

    /*
     * Edging
     */

    /**
     * Trims string (at both ends). Result is returned as a new String.
     * @param boolean | string $trimChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function trim($trimChars = false) {
        /* @var $plugin Plugin\Edging */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_EDGING);
        return $plugin->trim($this, $trimChars);
    }

    /**
     * Trims string (at left). Result is returned as a new String.
     * @param boolean | string $trimChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function leftTrim($trimChars = false) {
        /* @var $plugin Plugin\Edging */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_EDGING);
        return $plugin->leftTrim($this, $trimChars);
    }

    /**
     * Trims string (at right). Result is returned as a new String.
     * @param boolean | string $trimChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function rightTrim($trimChars = false) {
        /* @var $plugin Plugin\Edging */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_EDGING);
        return $plugin->rightTrim($this, $trimChars);
    }

    /**
     * Pads string to the left with another string. Result is returned as a new String.
     * @param int $length Pad length
     * @param string|String $padString Pad string, may be truncated if doesn't fit exactly.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function padLeft($length, $padString = ' ') {
        /* @var $plugin Plugin\Edging */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_EDGING);
        return $plugin->padLeft($this, $length, $padString);
    }

    /**
     * Pads string to the right with another string. Result is returned as a new String.
     * @param int $length Pad length
     * @param string|String $padString Pad string, may be truncated if doesn't fit exactly.
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function padRight($length, $padString = ' ') {
        /* @var $plugin Plugin\Edging */
        $plugin = $this->plugins()->get(PluginFactory::PLUGIN_EDGING);
        return $plugin->padRight($this, $length, $padString);
    }

    /* ===================================================== */
    /* String appending and prepending (no plugin involved)  */
    /* ===================================================== */

    /**
     * Appends a string after this one.
     * This method changes current String.
     * @param String|string $another
     * @return String
     * @throws IllegalArgumentException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function append($another) {
        if ($another instanceof String) {
            $this->string .= $another->string;
        } elseif (is_string($another)) {
            $this->string .=$another;
        } else {
            throw new IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
        return $this;
    }

    /**
     * Appends multiple strings to current one, using a given glue string.
     * This method changes current String.
     * @param string $glue
     * @param string $string1,[$string2,...]
     * @return String
     * @throws IllegalArgumentException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function appendMultiple($glue) {
        // Check for enough parameters
        if (func_num_args() <= 1) {
            return $this;
        }

        // Do append of multiple strings
        $arguments = func_get_args();
        array_shift($arguments);
        foreach ($arguments as $k => $argument) {
            if ($argument instanceof String) {
                $this->string .= $glue . $argument->string;
            } elseif (is_string($argument)) {
                $this->string .= $glue . $argument;
            } else {
                throw new IllegalArgumentException('Argument number ' . ($k + 2) . ' is not an instance of \Falx\Type\String or a string literal');
            }
        }
        return $this;
    }

    /**
     * Prepends a string before this one.
     * This method changes current String.
     * @param String|string $another
     * @return String
     * @throws IllegalArgumentException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function prepend($another) {
        if ($another instanceof String) {
            $this->string = $another->string . $this->string;
        } elseif (is_string($another)) {
            $this->string = $another . $this->string;
        } else {
            throw new IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
        return $this;
    }

    /**
     * Appends multiple strings to current one, using a given glue string.
     * This method changes current String.
     * @param string $glue
     * @param string $string1,[$string2,...]
     * @return String
     * @throws IllegalArgumentException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function prependMultiple($glue) {
        // Check for enough parameters
        if (func_num_args() <= 1) {
            return $this;
        }

        // Do prepend of multiple strings
        $arguments = func_get_args();
        array_shift($arguments);
        $argumentsCount = count($arguments);
        $arguments = array_reverse($arguments);
        foreach ($arguments as $k => $argument) {
            if ($argument instanceof String) {
                $this->string = $argument->string . $glue . $this->string;
            } elseif (is_string($argument)) {
                $this->string = $argument . $glue . $this->string;
            } else {
                throw new IllegalArgumentException('Argument number ' . ($argumentsCount - $k + 1) . ' is not an instance of \Falx\Type\String or a string literal');
            }
        }

        return $this;
    }

}
