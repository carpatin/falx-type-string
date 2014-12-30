<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;

/**
 * String case utility functions.F
 * 
 * @author Dan Homorodean
 */
interface Casing {

    function toUppercase(String $string);

    function toLowercase(String $string);

    function lowercaseFirst(String $string, $count = 1);

    function uppercaseFirst(String $string, $count = 1);

    function lowercaseWords(String $string);

    function uppercaseWords(String $string);

    function camelCaseToUnderscore(String $string);

    function undescoreToCamelCase(String $string);
}
