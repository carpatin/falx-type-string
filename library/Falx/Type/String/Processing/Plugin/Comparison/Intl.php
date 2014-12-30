<?php

namespace Falx\Type\String\Processing\Plugin\Comparison;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Comparison as ComparisonInterface;
use Falx\Type\String\Processing\Plugin\Comparison\Custom as CustomImplementation;
use Falx\Type\String\Processing\PluginFactory;

/**
 * Implementation of the comparison plugin interface using INTL.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Intl implements ComparisonInterface {

    /**
     * Uses INTL extension Collator class to provide functionality
     * @param String $first
     * @param String $second
     * @return int
     */
    public function compareTo(String $first, String $second) {
        $collator = new \Collator(setlocale(LC_COLLATE, 0));
        return $collator->compare($first->literal(), $second->literal());
    }

    /**
     * Falls back to basic PHP function to provide the functionality.
     * @param String $first
     * @param String $second
     * @return boolean
     */
    public function equals(String $first, String $second) {
        // Fallback to basic PHP
        return $first->literal() === $second->literal();
    }

    /**
     * Falls back to the custom implementation to provide the functionality.
     * @param String $first
     * @param String $second
     * @return boolean
     */
    public function equalsIgnoringCase(String $first, String $second) {
        //Fallback to custom implementation
        /* @var $comparisonPlugin CustomImplementation */
        $comparisonPlugin = $this->plugins()->getImplementation(PluginFactory::PLUGIN_COMPARISON, 'Custom');
        return $comparisonPlugin->equalsIgnoringCase($first, $second);
    }

    /**
     * Returns plugin factory
     * @return PluginFactory
     */
    protected function plugins() {
        return PluginFactory::getInstance();
    }

}
