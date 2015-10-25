<?php

include 'config.php';

use Falx\Type\String;

//TRIMMING

$s = new String('  <<--  nihil sine deo  -->>  ');
print '|' . $s->leftTrim() . '|' . PHP_EOL;
print '|' . $s->rightTrim() . '|' . PHP_EOL;
print '|' . $s->trim() . '|' . PHP_EOL;

print '|' . $s->leftTrim('<->') . '|' . PHP_EOL;
print '|' . $s->rightTrim('<->') . '|' . PHP_EOL;
print '|' . $s->trim('<->') . '|' . PHP_EOL;


//PADDING

$s = new String('PADDED');
print '|' . $s->padLeft(10) . '|' . PHP_EOL;
print '|' . $s->padRight(10) . '|' . PHP_EOL;
print '|' . $s->padLeft(10, '<=-') . '|' . PHP_EOL;
print '|' . $s->padRight(10, '-=>') . '|' . PHP_EOL;

print '|' . $s->padLeft(11, 'ășț') . '|' . PHP_EOL;
print '|' . $s->padRight(11, 'ășț') . '|' . PHP_EOL;
