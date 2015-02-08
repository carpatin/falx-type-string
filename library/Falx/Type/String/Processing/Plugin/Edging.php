<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Edging extends PluginInterface
{

    function trim(String $string, $trimChars = false);

    function leftTrim(String $string, $trimChars = false);

    function rightTrim(String $string, $trimChars = false);

    function padLeft(String $string, $length, $padString = ' ');

    function padRight(String $string, $length, $padString = ' ');
}
