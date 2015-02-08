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

interface Html extends PluginInterface
{

    function encodeHtmlEntities(String $string);

    function decodeHtmlEntities(String $string);

    function encodeHtmlSpecialChars(String $string);

    function decodeHtmlSpecialChars(String $string);

    function stripTags(String $string);
}
