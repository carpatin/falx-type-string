<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Splitting extends PluginInterface {

    function delimiterSplit(String $string, $delimiters);

    function chunkSplit(String $string, $chunkLength);
}
