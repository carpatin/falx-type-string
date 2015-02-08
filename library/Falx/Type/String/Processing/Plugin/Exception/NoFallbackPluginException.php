<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Exception;

use Falx\Type\String\Processing\Plugin\Exception;

/**
 * Thrown when a plugin tries to use a fallback plugin implementation of the same 
 * functionality and no fallback exists.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class NoFallbackPluginException extends Exception
{
    // Empty
}
