<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Url extends PluginInterface {

    function urlEncode(String $string, $rfc);

    function urlDecode(String $string, $rfc);
}
