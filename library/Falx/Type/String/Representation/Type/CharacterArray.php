<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Representation\Type;

use Falx\Type\String\Representation\Type;
use Falx\Type\String;

/**
 * Character array representation of an UTF-8 string.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class CharacterArray implements Type, \Countable, \ArrayAccess
{

    /**
     * The array of UTF-8 characters
     * @var array 
     */
    private $characters;

    /**
     * CharacterArray constructor
     * @param string $string
     */
    public function __construct($string = null)
    {
        if ($string !== null) {
            $this->characters = $this->getUtf8Characters($string);
        }
    }

    /**
     * Parses the given string and returns an array with strings, each of them 
     * representing an UTF-8 character (a codepoint sequence).
     * @param string $string
     * @return array
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function getUtf8Characters($string)
    {
        $characters = array();
        $current = 0;
        while (isset($string[$current])) {
            $first = $string[$current];
            $second = isset($string[$current + 1]) ? $string[$current + 1] : null;
            $third = isset($string[$current + 2]) ? $string[$current + 2] : null;
            $fourth = isset($string[$current + 3]) ? $string[$current + 3] : null;

            if (bin2hex($first & "\x80") == bin2hex("\x00")) {
                // ASCII range character
                $characters[] = $first;
                $current ++;
            } elseif (bin2hex($first & "\xE0") == bin2hex("\xC0")) {
                // Two codepoints character
                if ($second !== null && bin2hex($second & "\xC0") == bin2hex("\x80")) {
                    $characters[] = "$first$second";
                }
                $current+=2;
            } elseif (bin2hex($first & "\xF0") == bin2hex("\xE0")) {
                // Three codepoints character
                if ($second !== null && bin2hex($second & "\xC0") == bin2hex("\x80") && $third !== null && bin2hex($third & "\xC0") == bin2hex("\x80")) {
                    $characters[] = "$first$second$third";
                }
                $current+=3;
            } elseif (bin2hex($first & "\xF8") == bin2hex("\xF0")) {
                // Four codepoints character
                if ($second !== null && bin2hex($second & "\xC0") == bin2hex("\x80") && $third !== null && bin2hex($third & "\xC0") == bin2hex("\x80") && $fourth !== null && bin2hex($fourth & "\xC0") == bin2hex("\x80")) {
                    $characters[] = "$first$second$third$fourth";
                }
                $current+=4;
            }
        }
        return $characters;
    }

    /*
     * Countable implementation
     */

    /**
     * Counts and returns the count of the code points array.
     * @return int
     */
    public function count()
    {
        return count($this->characters);
    }

    /*
     * ArrayAccess implementation
     */

    /**
     * Offset exists
     * @param int $offset
     * @return string
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->characters);
    }

    /**
     * Offset get
     * @param int $offset
     * @return string
     */
    public function offsetGet($offset)
    {
        return $this->characters[$offset];
    }

    /**
     * Offset set
     * @param int $offset
     * @param string $value
     */
    public function offsetSet($offset, $value)
    {
        if (!is_string($value)) {
            throw new Exception('Expected a string value to set');
        }
        $this->characters[$offset] = $value;
    }

    /**
     * Offset unset
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->characters[$offset]);
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
        return new String(implode($this->characters));
    }

}
