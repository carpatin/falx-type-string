<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Edging;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Edging as EdgingInterface;
use Falx\Type\String\Processing\Plugin\Base as PluginBase;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;
use Falx\Type\String\Processing\Util\CharacterClasses;

/**
 * Custom edging plugin
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Custom extends PluginBase implements EdgingInterface {

    /**
     * Performs left trim on the string
     * @param String $string
     * @param string $additionalChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function leftTrim(String $string, $additionalChars = false) {
        // Get an array with the whitespace characters
        $whitespaceChars = CharacterClasses::getWhitespaceChars();

        // Add aditional chars to trim if provided
        if ($additionalChars !== false) {
            /* @var $additional CharacterArray */
            $additional = Registry::getInstance()->getRepresentation($additionalChars);
            for ($i = 0, $count = count($additional); $i < $count; $i++) {
                $whitespaceChars[] = $additional[$i];
            }
        }

        /* @var $chars CharacterArray */
        $chars = Registry::getInstance()->getRepresentation($string->literal());
        for ($i = 0, $count = count($chars); $i < $count; $i++) {
            $char = $chars[$i];
            if (in_array($char, $whitespaceChars)) {
                unset($chars[$i]);
            } else {
                break;
            }
        }

        $chars->resetIndices();
        return $chars->toString();
    }

    /**
     * Performs right trim on the string
     * @param String $string
     * @param string $additionalChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function rightTrim(String $string, $additionalChars = false) {
        // Get an array with the whitespace characters
        $whitespaceChars = CharacterClasses::getWhitespaceChars();

        // Add aditional chars to trim if provided
        if ($additionalChars !== false) {
            /* @var $additional CharacterArray */
            $additional = Registry::getInstance()->getRepresentation($additionalChars);
            for ($i = 0, $count = count($additional); $i < $count; $i++) {
                $whitespaceChars[] = $additional[$i];
            }
        }

        /* @var $chars CharacterArray */
        $chars = Registry::getInstance()->getRepresentation($string->literal());
        $count = count($chars);
        for ($i = $count - 1; $i >= 0; $i--) {
            $char = $chars[$i];
            if (in_array($char, $whitespaceChars)) {
                unset($chars[$i]);
            } else {
                break;
            }
        }

        $chars->resetIndices();
        return $chars->toString();
    }

    /**
     * Performs trim on the string at both ends.
     * @param String $string
     * @param string $additionalChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function trim(String $string, $additionalChars = false) {
        // Get an array with the whitespace characters
        $whitespaceChars = CharacterClasses::getWhitespaceChars();

        // Add aditional chars to trim if provided
        if ($additionalChars !== false) {
            /* @var $additional CharacterArray */
            $additional = Registry::getInstance()->getRepresentation($additionalChars);
            for ($i = 0, $count = count($additional); $i < $count; $i++) {
                $whitespaceChars[] = $additional[$i];
            }
        }

        /* @var $chars CharacterArray */
        $chars = Registry::getInstance()->getRepresentation($string->literal());
        for ($i = 0, $count = count($chars); $i < $count; $i++) {
            $char = $chars[$i];
            if (in_array($char, $whitespaceChars)) {
                unset($chars[$i]);
            } else {
                break;
            }
        }

        $chars->resetIndices();

        $count = count($chars);
        for ($i = $count - 1; $i >= 0; $i--) {
            $char = $chars[$i];
            if (in_array($char, $whitespaceChars)) {
                unset($chars[$i]);
            } else {
                break;
            }
        }

        return $chars->toString();
    }

    /**
     * Pads a string with given pad string to the left side or the right side.
     * @param String $string The string to pad
     * @param int $length
     * @param string $padString Default is ' '
     * @param string $side Defaults to 'left'. Other possible value is 'right'
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function pad(String $string, $length, $padString = ' ', $side = 'left') {
        $registry = Registry::getInstance();

        $literal = $string->literal();
        $chars = $registry->getRepresentation($literal);

        // Test if given string already the required length or larger
        $count = count($chars);
        if ($count >= $length) {
            return $string;
        }

        $diffChars = $length - $count;

        $padChars = $registry->getRepresentation($padString);
        $padCount = count($padChars);

        $times = (int) $diffChars / $padCount;
        $remainder = $diffChars % $padCount;

        $finalString = null;
        if ($side === 'left') {
            $finalString = str_repeat($padString, $times) . join('', array_slice($padChars->toArray(), 0, $remainder)) . $literal;
        } elseif ($side === 'right') {
            $finalString = $literal . str_repeat($padString, $times) . join('', array_slice($padChars->toArray(), 0, $remainder));
        }
        return new String($finalString);
    }

    /**
     * Pads a string with given pad string to the left side.
     * @param String $string The string to pad
     * @param int $length
     * @param string $padString Default is ' '
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function padLeft(String $string, $length, $padString = ' ') {
        return $this->pad($string, $length, $padString, 'left');
    }

    /**
     * Pads a string with given pad string to the right side.
     * @param String $string The string to pad
     * @param int $length
     * @param string $padString Default is ' '
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function padRight(String $string, $length, $padString = ' ') {
        return $this->pad($string, $length, $padString, 'right');
    }

}
