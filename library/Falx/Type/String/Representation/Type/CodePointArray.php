<?php

namespace Falx\Type\String\Representation\Type;

use Falx\Type\String\Representation\Type;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;
use Falx\Type\String;

/**
 * Codepoints array representation of an UTF-8 string.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 * 
 * @todo Refactor this: wrong class name
 */
class CodePointArray implements Type, \Countable, \ArrayAccess
{

    /**
     * Array of string code points
     * @var array
     */
    private $codePoints;

    /**
     * Class constructor
     * @param string $string
     */
    public function __construct($string = null)
    {
        if ($string !== null) {
            // Get the characters array for the given string
            $characterArray = Registry::getInstance()->getRepresentation($string);
            // Load codepoints from given character array
            $this->codePoints = $this->getUtf8CodePoints($characterArray);
        }
    }

    /**
     * Iterates the character array and computes the array of unsigned integer 
     * code points.
     * @param CharacterArray $characters
     * @return array An array with decimal code points
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function getUtf8CodePoints(CharacterArray $characters)
    {
        $codePoints = array();
        foreach ($characters as $character) {
            $hexString = bin2hex($character);
            $codePoint = hexdec($hexString);
            $codePoints[] = $codePoint;
        }
        return $codePoints;
    }

    /*
     * ArrayAccess implementation
     */

    /**
     * Returns whether a codepoint exists at offset
     * @param int $offset
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->codePoints);
    }

    /**
     * Returns code point at given offset
     * @param int $offset
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function offsetGet($offset)
    {
        return $this->codePoints[$offset];
    }

    /**
     * Sets the code point at given offset
     * @param int $offset
     * @param int $value
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function offsetSet($offset, $value)
    {
        $this->codePoints[$offset] = $value;
    }

    /**
     * Unsets code point at index
     * @param int $offset
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function offsetUnset($offset)
    {
        unset($this->codePoints[$offset]);
    }

    /*
     * Countable implementation
     */

    /**
     * Returns code points count
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function count()
    {
        return count($this->codePoints);
    }

    /*
     * Implementation of Type interface
     */

    /**
     * Returns the String corresponding to this representation
     * @return String
     */
    public function toString()
    {
        $string = '';
        foreach ($this->codePoints as $codePoint) {
            $hexString = dechex($codePoint);
            $character = hex2bin($hexString);
            $string.=$character;
        }

        return new String($string);
    }

}
