<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\CaseFolding\Mapper\ChainableMapper;

use Falx\Type\String\Processing\Plugin\CaseFolding\Mapper\ChainableMapper;

class LatinLetters extends ChainableMapper
{

    function __construct()
    {
        parent::__construct();
    }

    protected function getMappingsFilename()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'LatinFolding.csv';
    }

}
