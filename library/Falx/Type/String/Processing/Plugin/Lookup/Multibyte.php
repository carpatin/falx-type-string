<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Lookup;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Lookup as LookupInterface;
use Falx\Type\String\Processing\Plugin\Base as PluginBase;

/**
 * Implementation of the lookup plugin interface using Multibyte String extension.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
class Multibyte extends PluginBase implements LookupInterface
{

    /**
     * @param String $subject
     * @param String $substring
     * @return int|boolean Returns the first position as integer or FALSE if substring was not found.
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function substringFirstPosition(String $subject, String $substring)
    {
        $haystack = $subject->literal();
        $needle = $substring->literal();
        return mb_strpos($haystack, $needle, 0, 'UTF-8');
    }

    /**
     * @param String $subject
     * @param String $substring
     * @return int|boolean Returns the last position as integer or FALSE if substring was not found.
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function substringLastPosition(String $subject, String $substring)
    {
        $haystack = $subject->literal();
        $needle = $substring->literal();
        return mb_strrpos($haystack, $needle, 0, 'UTF-8');
    }

    /**
     * @param String $subject
     * @param String $substring
     * @return int
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function substringCount(String $subject, String $substring)
    {
        $haystack = $subject->literal();
        $needle = $substring->literal();
        return mb_substr_count($haystack, $needle, 'UTF-8');
    }

}
