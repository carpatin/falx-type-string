<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Substring extends PluginInterface {

    function substring(String $string, $start, $length);

    function substringCompareTo(String $first, String $second, $start, $length);

    function substringReplace(String $subject, String $replacement, $start, $length);
}
