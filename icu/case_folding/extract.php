<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/*
 * Extracts case folding data into 4 CSV's based on the family of letters
 */

$file = new SplFileObject('CaseFolding.csv');
$file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
$file->setCsvControl(';');

$latin = new SplFileObject('LatinFolding.csv', 'w');
$cyrillic = new SplFileObject('CyrillicFolding.csv', 'w');
$greek = new SplFileObject('GreekFolding.csv', 'w');
$other = new SplFileObject('OtherFolding.csv', 'w');


foreach ($file as $row) {
    $name = strtolower($row[3]);
    $from = trim($row[0]);
    $to = trim($row[2]);
    $status = trim($row[1]);
    if (strpos($name, 'latin') !== false || strpos($name, 'roman') !== false) {
        $latin->fputcsv(array($from, $to, $status));
    } elseif (strpos($name, 'cyrillic') !== false) {
        $cyrillic->fputcsv(array($from, $to, $status));
    } elseif (strpos($name, 'greek') !== false) {
        $greek->fputcsv(array($from, $to, $status));
    } else {
        $other->fputcsv(array($from, $to, $status));
    }
}

$latin->fflush();
$cyrillic->fflush();
$greek->fflush();
$other->fflush();
