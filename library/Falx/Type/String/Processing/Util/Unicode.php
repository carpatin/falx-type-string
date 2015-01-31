<?php

namespace Falx\Type\String\Processing\Util;

/**
 * Provides Unicode to UTF-8 comversion functions.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Unicode
{

    /**
     * Converts a Unicode codepoint provided in HEX representation (with or without the U+ prefix) to 
     * the coresponding array of bytes that form the UTF-8 representation of the character.
     * @param string $hexCodepoint HEX representation of the codepoint. Examples: '1EA6' or 'U+1EA6'
     * @return array Array of character bytes. Concatenated, they represent one UTF-8 character.
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public static function hexCodepointToUTF8CharacterBytes($hexCodepoint)
    {
        if (strpos($hexCodepoint, 'U+') === 0) {
            $hexCodepoint = substr($hexCodepoint, 2);
        }

        $codepointValue = hexdec($hexCodepoint);
        $codepointBinary = decbin($codepointValue);
        $bytesCount = self::getUTF8BytesCountForCodepointValue($codepointValue);

        switch ($bytesCount) {
            case 1:
                $binaryRepresentationOfBytes = self::fillStringMaskWithBits('0xxxxxxx', $codepointBinary);
                break;
            case 2:
                $binaryRepresentationOfBytes = self::fillStringMaskWithBits('110xxxxx 10xxxxxx', $codepointBinary);
                break;
            case 3:
                $binaryRepresentationOfBytes = self::fillStringMaskWithBits('1110xxxx 10xxxxxx 10xxxxxx', $codepointBinary);
                break;
            case 4:
                $binaryRepresentationOfBytes = self::fillStringMaskWithBits('11110xxx 10xxxxxx 10xxxxxx 10xxxxxx', $codepointBinary);
                break;
            default:
                return [];
        }
        $characterBytes = self::convertToCharacterBytes($binaryRepresentationOfBytes);
        return $characterBytes;
    }

    /**
     * Converts a string representation of bytes (using binary notation) to an array of binary values (strings).
     * @param string $binaryRepresentation String representation in binary of bytes (bytes separated by space)
     * @return array Array of bytes decimal values
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private static function convertToCharacterBytes($binaryRepresentation)
    {
        $bytes = array_map(function($binary) {
            $decimal = bindec($binary);
            if ($decimal === 0) {
                return null;
            }
            $hex = dechex($decimal);

            // Fix odd length HEX representations
            if (strlen($hex) % 2 == 1) {
                $hex = '0' . $hex;
            }

            // Convert HEX representation to corresponding binary form
            $binary = hex2bin($hex);
            return $binary;
        }, explode(' ', $binaryRepresentation));
        return $bytes;
    }

    /**
     * Fills up a binary representation mask with given bits.
     * @param string $mask A mask made up from 1,0,x and space characters
     * @param string $bits A string containing the 1s and 0s to fill up the mask
     * @return string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private static function fillStringMaskWithBits($mask, $bits)
    {
        // Pad with zero bits to the left (leading zeros may be missing)
        $count = substr_count($mask, 'x');
        $bits = str_pad($bits, $count, 0, STR_PAD_LEFT);

        // Declare variable to use for iteration through bits
        $bitsPosition = 0;

        // Iterate mask and replace 'x' with corresponding bit
        for ($i = 0, $length = strlen($mask); $i < $length; $i++) {
            if ($mask[$i] == 'x') {
                $mask[$i] = $bits[$bitsPosition];
                $bitsPosition++;
            }
        }
        return $mask;
    }

    /**
     * Returns the number of Bytes for the UTF-8 representation of the given Unicode codepoint.
     * Codepoint is given as decimal/numeric value.
     * @param int $codepointValue
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private static function getUTF8BytesCountForCodepointValue($codepointValue)
    {
        if ($codepointValue <= 0x7F) {
            return 1;
        } elseif ($codepointValue <= 0x7FF) {
            return 2;
        } elseif ($codepointValue <= 0xFFFF) {
            return 3;
        } elseif ($codepointValue <= 0x10FFFF) {
            return 4;
        } else {
            return 0;
        }
    }

}
