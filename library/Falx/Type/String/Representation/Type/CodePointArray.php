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
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;
use Falx\Type\String;

/**
 * Codepoints array representation of an UTF-8 string.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 * 
 * @todo Refactor this: wrong class name, misunderstood notion
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
        for ($i = 0, $count = count($characters); $i < $count; $i++) {
            $character = $characters[$i];
            switch (strlen($character)) {
                case 1:
                    $codePoint = $character;
                    break;
                case 2:
                    $firstBinary = $this->characterToBinaryRepresentation($character[0]);
                    $secondBinary = $this->characterToBinaryRepresentation($character[1]);
                    $finalBinary = '00000' . substr($firstBinary, 3) . substr($secondBinary, 2);

                    $firstCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 0, 8));
                    $secondCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 8));
                    $codePoint = "{$firstCharacter}{$secondCharacter}";
                    break;

                case 3:
                    $firstBinary = $this->characterToBinaryRepresentation($character[0]);
                    $secondBinary = $this->characterToBinaryRepresentation($character[1]);
                    $thirdBinary = $this->characterToBinaryRepresentation($character[2]);
                    $finalBinary = substr($firstBinary, 4) . substr($secondBinary, 2) . substr($thirdBinary, 2);

                    $firstCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 0, 8));
                    $secondCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 8));
                    $codePoint = "{$firstCharacter}{$secondCharacter}";
                    break;
                case 4:
                    $firstBinary = $this->characterToBinaryRepresentation($character[0]);
                    $secondBinary = $this->characterToBinaryRepresentation($character[1]);
                    $thirdBinary = $this->characterToBinaryRepresentation($character[2]);
                    $fourthBinary = $this->characterToBinaryRepresentation($character[3]);
                    $finalBinary = '000' . substr($firstBinary, 5) . substr($secondBinary, 2) . substr($thirdBinary, 2) . substr($fourthBinary, 2);

                    $firstCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 0, 8));
                    $secondCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 8, 8));
                    $thirdCharacter = $this->binaryRepresentationToCharacter(substr($finalBinary, 16));
                    $codePoint = "{$firstCharacter}{$secondCharacter}{$thirdCharacter}";
                    break;
            }


            $codePoints[] = $codePoint;
        }
        return $codePoints;
    }

    /**
     * Converts binary string char/byte to binary string representation.
     * @param string $character
     * @return string
     */
    private function characterToBinaryRepresentation($character)
    {
        return decbin(hexdec(bin2hex($character)));
    }

    /**
     * Converts binary string representation of a byte to binary string char.
     * @param string $string
     * @return string
     */
    private function binaryRepresentationToCharacter($string)
    {
        $hex = dechex(bindec($string));
        // Fix odd length HEX representations
        if (strlen($hex) % 2 == 1) {
            $hex = '0' . $hex;
        }
        return hex2bin($hex);
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
     * Returns the representation of codepoints in U+ notation
     * @return string
     */
    public function __toString()
    {
        $string = '';
        foreach ($this->codePoints as $codePoint) {
            $string.= 'U+' . str_pad(bin2hex($codePoint), 4, '0', STR_PAD_LEFT);
        }

        return $string;
    }

    public function toString()
    {
        //TODO
    }

}
