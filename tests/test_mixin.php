<?php
include 'config.php';

use Falx\Type\String;

String::registerMixinFunction('withSuffix', function ($zis, $suffix) {
    return  $zis->toUppercase().$suffix;
});

$s = new String('hello there earth creature');
print $s->withSuffix('!!!');