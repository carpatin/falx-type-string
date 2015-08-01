<?php

include 'config.php';

use Falx\Type\String;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CodePointArray;

$string = new String("\$€¢");//U+0024
print $string;
/* @var $codePoints CodePointArray */
$codePoints = Registry::getInstance()->getRepresentation($string->literal(), Registry::TYPE_CODEPOINT_ARRAY);
echo $codePoints.PHP_EOL;


$string = new String("€"); //U+20AC
print $string;
/* @var $codePoints CodePointArray */
$codePoints = Registry::getInstance()->getRepresentation($string->literal(), Registry::TYPE_CODEPOINT_ARRAY);
echo $codePoints.PHP_EOL;


$string = new String("¢"); // U+00A2
print $string;
/* @var $codePoints CodePointArray */
$codePoints = Registry::getInstance()->getRepresentation($string->literal(), Registry::TYPE_CODEPOINT_ARRAY);
echo $codePoints.PHP_EOL;

$string = new String("𝌆"); //U+1D306
print $string;
/* @var $codePoints CodePointArray */
$codePoints = Registry::getInstance()->getRepresentation($string->literal(), Registry::TYPE_CODEPOINT_ARRAY);
echo $codePoints.PHP_EOL;





