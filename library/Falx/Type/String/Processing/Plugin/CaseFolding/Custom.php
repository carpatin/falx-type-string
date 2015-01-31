<?php

namespace Falx\Type\String\Processing\Plugin\CaseFolding;

use Falx\Type\String\Processing\Plugin\CaseFolding as CaseFoldingInterface;
use Falx\Type\String;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;
use Falx\Type\String\Processing\Plugin\CaseFolding\Mapper;
use Falx\Type\String\Processing\Util\Unicode;

/**
 * Custom case folding plugin
 * @todo Finish implementation
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Custom implements CaseFoldingInterface
{

    /**
     * Converts a String to lowercase
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toLowercase(String $string)
    {
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());

        $mapper = Mapper::getInstance();
        for ($i = 0, $count = count($characterArray); $i < $count; $i++) {
            $character = $characterArray[$i];
            $lowered = $mapper->lowercase($character);
            $characterArray[$i] = $lowered;
        }

        $loweredString = $characterArray->toString();
        return $loweredString;
    }

    /**
     * Converts String to uppercase
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function toUppercase(String $string)
    {
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());

        $mapper = Mapper::getInstance();
        for ($i = 0, $count = count($characterArray); $i < $count; $i++) {
            $character = $characterArray[$i];
            $upper = $mapper->uppercase($character);
            $characterArray[$i] = $upper;
        }

        $uppercaseString = $characterArray->toString();
        return $uppercaseString;
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
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());
        $mapper = Mapper::getInstance();
        for ($i = 0, $length = count($characterArray); $i < $length && $i < $count; $i++) {
            $characterArray[$i] = $mapper->lowercase($characterArray[$i]);
        }
        $resultString = $characterArray->toString();
        return $resultString;
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
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());
        $mapper = Mapper::getInstance();
        for ($i = 0, $length = count($characterArray); $i < $length && $i < $count; $i++) {
            $characterArray[$i] = $mapper->uppercase($characterArray[$i]);
        }
        $resultString = $characterArray->toString();
        return $resultString;
    }

    /**
     * Lowercases words
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lowercaseWords(String $string)
    {
        return $this->applyCaseToWords($string, self::CASE_WORDS_LOWER);
    }

    /**
     * Uppercases words
     * @param String $string
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function uppercaseWords(String $string)
    {
        return $this->applyCaseToWords($string, self::CASE_WORDS_UPPER);
    }

    /*
     * Apply case to words constants
     */

    const CASE_WORDS_LOWER = 1;
    const CASE_WORDS_UPPER = 2;

    /**
     * Applies specified case to words, converting the first letter of each word 
     * to the specified case.
     * @param String $string
     * @param int $case One of CASE_WORDS_* constants 
     * @return String
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function applyCaseToWords(String $string, $case)
    {
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());
        $mapper = Mapper::getInstance();
        $dividers = $this->getWordDividers();

        for ($i = 0, $length = count($characterArray); $i < $length; $i++) {
            $current = $characterArray[$i];
            $candidate = false;
            $position = false;
            if ($this->inArray($current, $dividers) && ($i + 1) < $length) {
                $candidate = $characterArray[$i + 1];
                $position = $i + 1;
            } elseif ($i == 0) {
                $candidate = $current;
                $position = 0;
            }

            if ($candidate !== false) {
                switch ($case) {
                    case self::CASE_WORDS_LOWER:
                        $characterArray[$position] = $mapper->lowercase($candidate);
                        break;
                    case self::CASE_WORDS_UPPER:
                        $characterArray[$position] = $mapper->uppercase($candidate);
                        break;
                }
            }
        }
        $resultString = $characterArray->toString();
        return $resultString;
    }

    /**
     * String enabled in array test function.
     * @param string $value
     * @param array $array
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function inArray($value, array $array)
    {
        foreach ($array as $test) {
            if (strcmp($value, $test) == 0) {
                return true;
            }
        }
        return false;
    }

    public function undescoreToCamelCase(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function camelCaseToUnderscore(String $string)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Local cache of divider characters
     * @var array
     */
    private $wordDividers;

    /**
     * Returns an array with UTF8 representation strings containing Unicode word dividers
     * @return array
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function getWordDividers()
    {
        if ($this->wordDividers === null) {

            $this->wordDividers = [
                /*
                 * Whitespace characters
                 */
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0009')), // horizontal tab
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000A')), // line feed
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000B')), // line tabulation
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000C')), // form feed
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000D')), // carriage return
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0020')), // ASCII space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0085')), // next line
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+00A0')), // no-break space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+1680')), // ogham space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2000')), // en quad
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2001')), // em quad
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2002')), // en space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2003')), // em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2004')), // three-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2005')), // four-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2006')), // six-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2007')), // figure space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2008')), // punctuation space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2009')), // thin space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+200A')), // hair space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2028')), // line separator
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2029')), // paragraph separator
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+202F')), // narrow no-break space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+205F')), // medium mathematical space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+3000')), // ideographic space
            ];
        }

        return $this->wordDividers;
    }

}
