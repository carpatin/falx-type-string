<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

/**
 * Length plugin interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface Length extends PluginInterface {

    function length(String $string);
}
