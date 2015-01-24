<?php

namespace Falx\Type\String\Representation;

/**
 * SRepresentation type interface
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
interface Type
{

    /**
     * Must be implemented by concrete representation to returns the representation 
     * as a String instance.
     */
    function toString();
}
