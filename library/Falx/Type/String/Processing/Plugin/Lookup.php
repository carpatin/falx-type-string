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
 * Lookup plugin interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface Lookup extends PluginInterface
{

    /**
     * @param String $subject
     * @param String $substring
     * @return int|boolean
     */
    function substringFirstPosition(String $subject, String $substring);

    /**
     * @param String $subject
     * @param String $substring
     * @return int|boolean
     */
    function substringLastPosition(String $subject, String $substring);

    /**
     * @param String $subject
     * @param String $substring
     * @return int
     */
    function substringCount(String $subject, String $substring);
}
