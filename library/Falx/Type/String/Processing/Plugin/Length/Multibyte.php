<?php

namespace Falx\Type\String\Processing\Plugin\Length;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Length as LengthInterface;

/**
 * Implementation of the length plugin interface using Multibyte String extension.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Multibyte implements LengthInterface {

    /**
     * Implements the length method using Multibyte String extension.
     * @param String $string
     * @return int
     */
    public function length(String $string) {
        mb_internal_encoding('UTF-8');
        return mb_strlen($string->literal());
    }

}
