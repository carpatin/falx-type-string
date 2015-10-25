<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Representation;

/**
 * Representation type interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface Type {

    /**
     * Must be implemented by concrete representation to return the representation 
     * as a String instance.
     */
    function toString();

    /**
     * Must be implemented by concrete representation to return an array with elements representing the characters.
     */
    function toArray();
}
