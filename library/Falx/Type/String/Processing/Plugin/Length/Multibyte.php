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

/**
 * Implementation of the length plugin interface using Multibyte String extension.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Multibyte extends PluginBase implements LengthInterface
{

    /**
     * Implements the length method using Multibyte String extension.
     * @param String $string
     * @return int
     */
    public function length(String $string)
    {
        mb_internal_encoding('UTF-8');
        return mb_strlen($string->literal());
    }

}
