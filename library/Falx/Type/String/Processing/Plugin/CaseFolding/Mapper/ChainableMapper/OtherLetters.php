<?php

namespace Falx\Type\String\Processing\Plugin\CaseFolding\Mapper\ChainableMapper;

use Falx\Type\String\Processing\Plugin\CaseFolding\Mapper\ChainableMapper;

class OtherLetters extends ChainableMapper
{

    function __construct()
    {
        parent::__construct();
    }

    protected function getMappingsFilename()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'OtherFolding.csv';
    }

}