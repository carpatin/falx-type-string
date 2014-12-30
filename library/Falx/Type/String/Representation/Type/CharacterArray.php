<?php

namespace Falx\Type\String\Representation\Type;

/**
 * Character array representation of an UTF-8 string.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class CharacterArray implements \Countable, \ArrayAccess {

    /**
     * The array of UTF-8 characters
     * @var array 
     */
    private $characters;

    /**
     * CharacterArray constructor
     * @param string $string
     */
    public function __construct($string) {
        $this->characters = $this->getUtf8Characters($string);
    }

    /**
     * Parses the given string and returns an array with strings, each of them 
     * representing an UTF-8 character (a codepoint sequence).
     * @param string $string
     * @return array
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function getUtf8Characters($string) {
        $characters = array();
        $current = 0;
        while (isset($string[$current])) {
            $first = $string[$current];
            $second = isset($string[$current + 1]) ? $string[$current + 1] : null;
            $third = isset($string[$current + 2]) ? $string[$current + 2] : null;
            $fourth = isset($string[$current + 3]) ? $string[$current + 3] : null;
            if ($first & "\x80" === "\x00") {
                // ASCII range character
                $characters[] = $first;
                $current ++;
            } elseif ($first & "\xE0" === "\C0") {
                // Two codepoints character
                if ($second !== null && $second & "\xC0" === "\x80") {
                    $characters[] = "$first$second";
                }
                $current+=2;
            } elseif ($first & "\xF0" === "\xE0") {
                // Three codepoints character
                if ($second !== null && $second & "\xC0" === "\x80" && $third !== null && $third & "\xC0" === "\x80") {
                    $characters[] = "$first$second$third";
                }
                $current+=3;
            } elseif ($first & "\xF8" === "\xF0") {
                // Four codepoints character
                if ($second !== null && $second & "\xC0" === "\x80" && $third !== null && $third & "\xC0" === "\x80" && $fourth !== null && $fourth & "\xC0" === "\x80") {
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
    public function count() {
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
    public function offsetExists($offset) {
        return array_key_exists($offset, $this->characters);
    }

    /**
     * Offset get
     * @param int $offset
     * @return string
     */
    public function offsetGet($offset) {
        return $this->characters[$offset];
    }

    /**
     * Offset set
     * @param int $offset
     * @param string $value
     */
    public function offsetSet($offset, $value) {
        if (!is_string($value)) {
            throw new Exception('Expected a string value to set');
        }
        $this->characters[$offset] = $value;
    }

    /**
     * Offset unset
     * @param int $offset
     */
    public function offsetUnset($offset) {
        unset($this->characters[$offset]);
    }

}
