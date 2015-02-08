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

interface Substring extends PluginInterface
{

    function substring(String $string, $start, $length);

    function substringCompareTo(String $first, String $second, $start, $length);

    function substringReplace(String $subject, String $replacement, $start, $length);
}
