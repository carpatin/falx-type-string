<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Lookup extends PluginInterface {

    function substringFirstPosition(String $subject, String $substring);

    function substringLastPosition(String $subject, String $substring);

    function substringCount(String $subject, String $substring);
}
