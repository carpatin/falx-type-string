<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Length;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Length as LengthInterface;
use Falx\Type\String\Processing\Plugin\Base as PluginBase;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;

/**
 * Custom implementation of the length plugin interface.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Custom extends PluginBase implements LengthInterface
{

    /**
     * Implements the length method using characters array of the given string.
     * @param String $string
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function length(String $string)
    {
        /* @var $characterArray CharacterArray */
        $characterArray = Registry::getInstance()->getRepresentation($string->literal());
        return $characterArray->count();
    }

}
