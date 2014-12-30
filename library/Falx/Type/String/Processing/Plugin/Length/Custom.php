<?php

namespace Falx\Type\String\Processing\Plugin\Length;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Length as LengthInterface;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;

/**
 * Custom implementation of the length plugin interface using code point arrays.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Custom implements LengthInterface {

    /**
     * Implements the length method using characters array of the given string.
     * @param String $string
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function length(String $string) {
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());
        return $characterArray->count();
    }

}
