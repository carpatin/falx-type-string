<?php

namespace Falx\Type\String\Processing\Plugin\CaseFolding;

use Falx\Type\String\Processing\Plugin\CaseFolding as CaseFoldingInterface;
use Falx\Type\String;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;
use Falx\Type\String\Processing\Plugin\CaseFolding\Mapper;

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

    public function toUppercase(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function camelCaseToUnderscore(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function lowercaseFirst(String $string, $count = 1)
    {
        throw new \Exception('Not implemented');
    }

    public function lowercaseWords(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function undescoreToCamelCase(String $string)
    {
        throw new \Exception('Not implemented');
    }

    public function uppercaseFirst(String $string, $count = 1)
    {
        throw new \Exception('Not implemented');
    }

    public function uppercaseWords(String $string)
    {
        throw new \Exception('Not implemented');
    }

}
