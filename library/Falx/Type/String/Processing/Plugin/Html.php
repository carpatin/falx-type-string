<?php

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

interface Html extends PluginInterface {

    function encodeHtmlEntities(String $string);

    function decodeHtmlEntities(String $string);

    function encodeHtmlSpecialChars(String $string);

    function decodeHtmlSpecialChars(String $string);

    function stripTags(String $string);
}
