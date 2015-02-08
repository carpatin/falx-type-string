<?php
include 'config.php';

use Falx\Type\String;

$s = new String("ĂȚșâășțΚϱГ\xE1\xBA\x9E");
print $s->toLowercase();
print "\n";
print $s->toUppercase();


print "\n";
print $s->lowercaseFirst();
print "\n";
print $s->lowercaseFirst(5);
print "\n";
print $s->lowercaseFirst()->uppercaseFirst();
print "\n";
print $s->lowercaseFirst(100)->uppercaseFirst(100);
print "\n";

$s = new String('sS');
print $s->toLowercase();
print "\n";
print $s->toUppercase();
print "\n";

$s = new String('și ce să-ți dau când nu dețin nimic');
print $s->uppercaseWords();
print "\n";
print $s->uppercaseWords()->lowercaseWords();
print "\n";


// Camel case to underscore

$camelCasedSimple = new String("URLBasedRemoteCaller");
print $camelCasedSimple->camelCaseToUnderscore();
print "\n";

$camelCased = new String("getAmmountVAT24FromUSD");
print $camelCased->camelCaseToUnderscore();
print "\n";

// Underscore to camel case

$underscored = new String("get_the_max_length");
print $underscored->undescoreToCamelCase();
print "\n";

$underscored = new String("_get_the_max_length_");
print $underscored->undescoreToCamelCase();
print "\n";

$underscored = new String("get__the__max__length");
print $underscored->undescoreToCamelCase();
print "\n";