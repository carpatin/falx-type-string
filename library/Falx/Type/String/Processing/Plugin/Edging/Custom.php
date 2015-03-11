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
class Custom extends PluginBase implements EdgingInterface
{

    /**
     * Performs left trim on the string
     * @param String $string
     * @param string $additionalChars
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function leftTrim(String $string, $additionalChars = false)
    {
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
    public function rightTrim(String $string, $additionalChars = false)
    {
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
    public function trim(String $string, $additionalChars = false)
    {
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

    public function padLeft(String $string, $length, $padString = ' ')
    {
        throw new \Exception('Not implemented');
    }

    public function padRight(String $string, $length, $padString = ' ')
    {
        throw new \Exception('Not implemented');
    }

}
