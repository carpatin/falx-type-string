<?php

namespace Falx\Type;

use Falx\Type\String\StringInterface;

class OldString implements StringInterface {

    private $string;

    /**
     * Constructor
     * @param \Falx\Type\String|string $string
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function __construct($string = '') {
        $this->testForRequiredExtensions();

        if (is_string($string)) {
            $this->string = $string;
        } elseif ($string instanceof String) {
            $this->string = $string->string;
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is neither a String nor a string literal');
        }
    }

    private function testForRequiredExtensions() {
        //TODO
    }

    /**
     * Creates and returns a copy of the current String instance
     * @return \Falx\Type\String
     */
    public function copy() {
        return new String($this->string);
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

    /*
     * COMPARISON METHODS
     */

    /**
     * Test content equality between two strings
     * @param \Falx\Type\String|string $another
     * @return boolean
     */
    public function equals($another) {
        if ($another instanceof String) {
            return $this->string == $another->string;
        } elseif (is_string($another)) {
            return $this->string == $another;
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
    }

    /**
     * Test content equality between two strings ignoring case
     * @param \Falx\Type\String $another
     * @return boolean
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function equalsIgnoreCase($another) {
        if ($another instanceof String) {
            return mb_strtolower($this->string) == mb_strtolower($another->string);
        } elseif (is_string($another)) {
            return mb_strtolower($this->string) == mb_strtolower($another);
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
    }

    /**
     * Compares two strings lexicographically.
     * @param \Falx\Type\String|string $another
     * @return int the value 0 if the argument string is equal to this string; 
     * a value less than 0 if this string is lexicographically less than the string argument; 
     * and a value greater than 0 if this string is lexicographically greater than the string argument.
     */
    public function compareTo($another) {

        if ($another instanceof String) {
            $another = $another->string;
        } elseif (!is_string($another)) {
            throw new \Falx\Exception\IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }

        if (class_exists('Collator')) {
            //use intl Collator
            $collator = new \Collator(setlocale(LC_COLLATE, 0));
            return $collator->compare($this->string, $another);
        } else {
            //rely on strcmp()
            $result = strcmp($this->string, $another);
            if ($result > 0) {
                return 1;
            } elseif ($result < 0) {
                return -1;
            } else {
                return 0;
            }
        }
    }

    /*
     * LOWERCASE AND UPPERCASE
     */

    /**
     * Lowercase string
     * @return \Falx\Type\String
     */
    public function toLowerCase() {
        $this->string = mb_strtolower($this->string);
        return $this;
    }

    /**
     * Uppercase string
     * @return \Falx\Type\String
     */
    public function toUpperCase() {
        $this->string = mb_strtoupper($this->string);
        return $this;
    }

    /*
     * APPENDERS AND PREPENDERS 
     */

    /**
     * Appends a string after this one.
     * @param \Falx\Type\String|string $another
     * @return \Falx\Type\String
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function append($another) {
        if ($another instanceof String) {
            $this->string .= $another->string;
        } elseif (is_string($another)) {
            $this->string .=$another;
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
        return $this;
    }

    /**
     * Appends multiple strings to current one, using a given glue string
     * @param string $glue
     * @param string $string1,[$string2,...]
     * @return \Falx\Type\String
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function appendMultiple($glue) {
        if (func_num_args() > 1) {
            $arguments = func_get_args();
            array_shift($arguments);
            foreach ($arguments as $k => $argument) {
                if ($argument instanceof String) {
                    $this->string .= $glue . $argument->string;
                } elseif (is_string($argument)) {
                    $this->string .= $glue . $argument;
                } else {
                    throw new \Falx\Exception\IllegalArgumentException('Argument number ' . ($k + 2) . ' is not an instance of \Falx\Type\String or a string literal');
                }
            }
        }
        return $this;
    }

    /**
     * Prepends a string before this one.
     * @param \Falx\Type\String|string $another
     * @return \Falx\Type\String
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function prepend($another) {
        if ($another instanceof String) {
            $this->string = $another->string . $this->string;
        } elseif (is_string($another)) {
            $this->string = $another . $this->string;
        } else {
            throw new \Falx\Exception\IllegalArgumentException('Argument is not an instance of \Falx\Type\String or a string literal');
        }
        return $this;
    }

    /**
     * Appends multiple strings to current one, using a given glue string
     * @param string $glue
     * @param string $string1,[$string2,...]
     * @return \Falx\Type\String
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function prependMultiple($glue) {
        if (func_num_args() > 1) {
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
                    throw new \Falx\Exception\IllegalArgumentException('Argument number ' . ($argumentsCount - $k + 1) . ' is not an instance of \Falx\Type\String or a string literal');
                }
            }
        }
        return $this;
    }

    /*
     * TRIM METHODS
     */

    const TRIM_LEFT = 1;
    const TRIM_RIGHT = 2;
    const TRIM_BOTH = 3;

    /**
     * Trim (left , right or both ends) the string.
     * @param int $mode One of teh class TRIM_* constants
     * @param string $charList
     * @return \Falx\Type\String
     */
    public function trim($mode = self::TRIM_BOTH, $charList = null) {
        if ($charList === null) {
            $charList = " \t\n\r\0\x0B";
        }
        switch ($mode) {
            case self::TRIM_LEFT:
                $trimmed = ltrim($this->string, $charList);
                break;
            case self::TRIM_RIGHT:
                $trimmed = rtrim($this->string, $charList);
                break;
            case self::TRIM_BOTH:
                $trimmed = trim($this->string, $charList);
                break;
            default:
                $trimmed = $this->string;
                break;
        }
        $this->string = $trimmed;
        return $this;
    }

    /**
     * Performs space trimming, including Unicode spacing chars.
     * @param int $mode
     * @return \Falx\Type\String
     */
    public function unicodeTrim($mode = self::TRIM_BOTH) {
        switch ($mode) {
            case self::TRIM_LEFT:
                $trimmed = preg_replace("/(^\s+)/us", '', $this->string);
                break;
            case self::TRIM_RIGHT:
                $trimmed = preg_replace("/(\s+$)/us", '', $this->string);
                break;
            case self::TRIM_BOTH:
                $trimmed = preg_replace("/(^\s+)|(\s+$)/us", '', $this->string);
                break;
            default:
                $trimmed = $this->string;
                break;
        }
        $this->string = $trimmed;
        return $this;
    }

    /**
     * Extracts substring
     * @param int $start
     * @param int $length
     * @return \Falx\Type\String
     * @throws \Falx\Exception\IllegalArgumentException
     */
    public function substring($start, $length) {
        //check start and length arguments types
        if (!is_int($start)) {
            throw new \Falx\Exception\IllegalArgumentException('Argument $start must be of type int');
        }
        if (!is_int($length)) {
            throw new \Falx\Exception\IllegalArgumentException('Argument $length must be of type int');
        }

        $substring = mb_substr($this->string, $start, $length);
        return new String($substring);
    }

    /**
     * Checks if string matches pattern
     * @param string $pattern
     * @return boolean
     * @throws \Exception
     */
    public function matches($pattern) {
        $match = preg_match($pattern, $this->string);
        if ($match === false) {
            throw new \Exception("Regular expression match failed");
        }
        return $match === 1 ? true : false;
    }

}
