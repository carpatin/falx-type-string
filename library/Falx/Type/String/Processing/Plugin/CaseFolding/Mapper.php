<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\CaseFolding;

/**
 * Case folding lowercase/uppercase mapper. 
 * Chain of responsibility applied in this class.
 * Uses a chain of latin, greek, cyrillic and other characters mappers 
 * in order to optimize the mapping from lowercase to uppercase and vice versa.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Mapper
{

    /**
     * Singleton instance
     * @var Mapper 
     */
    private static $instance;

    /**
     * Singleton getter
     * @return Mapper
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * The first mapper in chain
     * @var Mapper\ChainableMapper
     */
    private $top;

    /**
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function __construct()
    {
        // Setup chain of mappers
        $latin = new Mapper\ChainableMapper\LatinLetters();
        $greek = new Mapper\ChainableMapper\GreekLetters();
        $cyrillic = new Mapper\ChainableMapper\CyrillicLetters();
        $other = new Mapper\ChainableMapper\OtherLetters();

        $cyrillic->setNext($other);
        $greek->setNext($cyrillic);
        $latin->setNext($greek);

        $this->top = $latin;
    }

    /**
     * Attempts to map the character to its lowercase.
     * @param string $character
     * @return string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lowercase($character)
    {
        $this->top->setMode(Mapper\ChainableMapper::MODE_UPPER_TO_LOWER);
        $lower = $this->top->lookup($character);

        if ($lower === false) {
            return $character;
        }

        return $lower;
    }

    /**
     * Attempts to map the character to its uppercase.
     * @param string $character
     * @return string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function uppercase($character)
    {
        $this->top->setMode(Mapper\ChainableMapper::MODE_LOWER_TO_UPPER);
        $upper = $this->top->lookup($character);

        if ($upper === false) {
            return $character;
        }

        return $upper;
    }

}
