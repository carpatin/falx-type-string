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

/**
 * Case folding plugin interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface CaseFolding
{

    function toUppercase(String $string);

    function toLowercase(String $string);

    function lowercaseFirst(String $string, $count = 1);

    function uppercaseFirst(String $string, $count = 1);

    function lowercaseWords(String $string);

    function uppercaseWords(String $string);

    function camelCaseToUnderscore(String $string);

    function undescoreToCamelCase(String $string);
}
