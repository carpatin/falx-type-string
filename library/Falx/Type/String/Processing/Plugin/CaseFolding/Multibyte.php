<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\CaseFolding;

use Falx\Type\String\Processing\Plugin\CaseFolding as CaseFoldingInterface;
use Falx\Type\String\Processing\Plugin\Base as BasePlugin;
use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Exception as PluginException;

/**
 * Multibyte extension based case folding plugin
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Multibyte extends BasePlugin implements CaseFoldingInterface
{

    /**
     * Converts a String to lowercase
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toLowercase(String $string)
    {
        $converted = mb_convert_case($string->literal(), MB_CASE_LOWER, 'UTF-8');
        return new String($converted);
    }

    /**
     * Converts String to uppercase
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toUppercase(String $string)
    {
        $converted = mb_convert_case($string->literal(), MB_CASE_UPPER, 'UTF-8');
        return new String($converted);
    }

    /**
     * Converts first character (or first $count characters) of String to lowercase.
     * @param String $string
     * @param int $count
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lowercaseFirst(String $string, $count = 1)
    {
        return $this->changeFirst($string, $count, MB_CASE_LOWER);
    }

    /**
     * Converts first character (or first $count characters) of String to uppercase.
     * @param String $string
     * @param int $count
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function uppercaseFirst(String $string, $count = 1)
    {
        return $this->changeFirst($string, $count, MB_CASE_UPPER);
    }

    /**
     * Converts first character (or first $count characters) of String to lowercase or uppercase depending on the mode.
     * @param String $string
     * @param type $count
     * @param type $mode
     * @return String
     * @throws PluginException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function changeFirst(String $string, $count, $mode)
    {
        if ($count < 1) {
            throw new PluginException('Invalid count provided. Value too small.');
        }

        if ($count >= mb_strlen($string->literal(), 'UTF-8')) {
            throw new PluginException('Invalid count provided. Value too big.');
        }

        $first = mb_substr($string->literal(), 0, (int) $count, 'UTF-8');
        $firstConverted = mb_convert_case($first, $mode, 'UTF-8');
        $remaining = mb_substr($string->literal(), (int) $count, null, 'UTF-8');
        return new String($firstConverted . $remaining);
    }

    public function lowercaseWords(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function uppercaseWords(String $string)
    {
        $converted = mb_convert_case($string->literal(), MB_CASE_TITLE, 'UTF-8');
        return new String($converted);
    }

    public function camelCaseToUnderscore(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function undescoreToCamelCase(String $string)
    {
        throw new \Exception('Not implemented');
    }

}
