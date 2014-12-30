<?php

namespace Falx\Type\String\Processing\Plugin\Comparison;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Comparison as ComparisonInterface;
use Falx\Type\String\Processing\Plugin\Casing as CasingInterface;
use Falx\Type\String\Processing\PluginFactory;
use Falx\Type\String\Representation\Registry;
use Falx\Type\String\Representation\Type\CharacterArray;

class Custom implements ComparisonInterface {

    /**
     * Custom implementation of compare to operation.
     * @param String $first
     * @param String $second
     * @return int One of 1, 0, -1
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function compareTo(String $first, String $second) {
        /* @var $firstChars CharacterArray */
        $firstChars = Registry::getInstance()->getRepresentation($first->literal());

        /* @var $secondChars CharacterArray */
        $secondChars = Registry::getInstance()->getRepresentation($second->literal());

        $firstCount = $firstChars->count();
        $secondCount = $secondChars->count();

        // Check if we can return based on lengths
        if ($firstCount > $secondCount) {
            return self::COMPARE_BIGGER;
        }
        if ($firstCount < $secondCount) {
            return self::COMPARE_SMALLER;
        }

        // Prepare code points arrays for codepoint to codepoint comparison
        /* @var $firstCodePoints CodePointArray */
        $firstCodePoints = Registry::getInstance()->getRepresentation($first->literal(), Registry::TYPE_CODEPOINT_ARRAY);
        /* @var $secondCodePoints CodePointArray */
        $secondCodePoints = Registry::getInstance()->getRepresentation($second->literal(), Registry::TYPE_CODEPOINT_ARRAY);
        $count = count($firstCodePoints);
        for ($i = 0; $i < $count; $i++) {
            if ($firstCodePoints[$i] !== $secondCodePoints[$i]) {
                return $firstCodePoints[$i] > $secondCodePoints[$i] ? self::COMPARE_BIGGER : self::COMPARE_SMALLER;
            }
        }

        return self::COMPARE_EQUAL;
    }

    /**
     * Custom implementation for the equals operation.
     * @param String $first
     * @param String $second
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equals(String $first, String $second) {
        return $first->literal() === $second->literal();
    }

    /**
     * Custom implementation for the equals ignoring case operation.
     * @param String $first
     * @param String $second
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function equalsIgnoringCase(String $first, String $second) {
        /* @var $casingPlugin CasingInterface */
        $casingPlugin = $this->plugins()->getImplementation(PluginFactory::PLUGIN_CASING, 'Custom');
        $lowercasedFirst = $casingPlugin->toLowercase($first);
        $lowercasedSecond = $casingPlugin->toLowercase($second);
        return $lowercasedFirst->literal() === $lowercasedSecond->literal();
    }

    /**
     * Returns plugin factory
     * @return PluginFactory
     */
    protected function plugins() {
        return PluginFactory::getInstance();
    }

}
