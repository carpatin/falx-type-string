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

use Falx\Type\String\Processing\Plugin\Exception\NoFallbackPluginException;

/**
 * Base abstract plugin class.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
abstract class Base
{

    /**
     * The next plugin in chain
     * @var Base 
     */
    private $next;

    /**
     * Sets the next plugin in chain.
     * @param Base $next
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function setNext(Base $next)
    {
        $this->next = $next;
    }

    /**
     * Returns fallback plugin
     * @return Base
     * @throws NoFallbackPluginException
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    protected function getFallback()
    {
        if ($this->next === null) {
            throw new NoFallbackPluginException();
        }
        return $this->next;
    }

}
