<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin;

use Falx\Type\String;
use Falx\Type\String\Processing\PluginInterface;

/**
 * Comparison plugin interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface Comparison extends PluginInterface
{

    /**
     * compareTo return values
     */
    const COMPARE_EQUAL = 0;
    const COMPARE_BIGGER = 1;
    const COMPARE_SMALLER = -1;

    function equals(String $first, String $second);

    function equalsIgnoringCase(String $first, String $second);

    function compareTo(String $first, String $second);
}
