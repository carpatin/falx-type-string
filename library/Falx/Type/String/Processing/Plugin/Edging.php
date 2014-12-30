<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Edging extends PluginInterface {

    function trim(String $string, $additionalChars = false);

    function leftTrim(String $string, $additionalChars = false);

    function rightTrim(String $string, $additionalChars = false);

    function padLeft(String $string, $length, $padString = ' ');

    function padRight(String $string, $length, $padString = ' ');
}
