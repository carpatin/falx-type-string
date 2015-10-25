<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Comparison;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Base as BasePlugin;
use Falx\Type\String\Processing\Plugin\Comparison as ComparisonInterface;

/**
 * Implementation of the comparison plugin interface using INTL.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Intl extends BasePlugin implements ComparisonInterface {

    /**
     * Uses intl extension Collator class to provide functionality
     * @param String $first
     * @param String $second
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function compareTo(String $first, String $second) {
        $collator = new \Collator(setlocale(LC_COLLATE, 0));
        return $collator->compare($first->literal(), $second->literal());
    }

    /**
     * Returns whether the strings are equal or not 
     * Uses the Collator class to accomplish that
     * @param String $first
     * @param String $second
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equals(String $first, String $second) {
        $collator = new \Collator(setlocale(LC_COLLATE, 0));
        return $collator->compare($first->literal(), $second->literal()) === 0;
    }

    /**
     * Returns whether the strings are equal or not, ignoring their case 
     * Falls back to the next implementation in chain to provide the functionality 
     * @param String $first
     * @param String $second
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equalsIgnoringCase(String $first, String $second) {
        //Fallback to another plugin implementation
        return $this->getFallback()->equalsIgnoringCase($first, $second);
    }

}
