<?php
include 'config.php';

use Falx\Type\String;

$s = new String('ĂȚșâășțΚϱГ');
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

$s = new String('și ce să-ți dau când nu dețin nimic');
print $s->uppercaseWords(); //TODO: fix s to uppercase S problem (basically finish all types of mappings)
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