<?php
include 'config.php';

use Falx\Type\String;

$s = new String('Ăsta sunt eu. Ți-am ștres până și ultimele informații. Κ ceva grecesc ϱ, ceva rusesc Г');
print $s->toLowercase();

