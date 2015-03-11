<?php

include 'config.php';

use Falx\Type\String;

$s = new String('  <<--  nihil sine deo  -->>  ');
print '|' . $s->leftTrim() . '|' . PHP_EOL;
print '|' . $s->rightTrim() . '|' . PHP_EOL;
print '|' . $s->trim() . '|' . PHP_EOL;

print '|' . $s->leftTrim('<->') . '|' . PHP_EOL;
print '|' . $s->rightTrim('<->') . '|' . PHP_EOL;
print '|' . $s->trim('<->') . '|' . PHP_EOL;

