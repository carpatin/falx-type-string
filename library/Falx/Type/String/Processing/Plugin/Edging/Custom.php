<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\Edging;

use Falx\Type\String;
use Falx\Type\String\Processing\Plugin\Edging as EdgingInterface;
use Falx\Type\String\Processing\Plugin\Base as PluginBase;

/**
 * @todo Implementation
 */
class Custom extends PluginBase implements EdgingInterface
{
   /*
        public function unicodeTrim($mode = self::TRIM_BOTH) {
        switch ($mode) {
            case self::TRIM_LEFT:
                $trimmed = preg_replace("/(^\s+)/us", '', $this->string);
                break;
            case self::TRIM_RIGHT:
                $trimmed = preg_replace("/(\s+$)/us", '', $this->string);
                break;
            case self::TRIM_BOTH:
                $trimmed = preg_replace("/(^\s+)|(\s+$)/us", '', $this->string);
                break;
            default:
                $trimmed = $this->string;
                break;
        }
        $this->string = $trimmed;
        return $this;
    }
    */
    
   public function leftTrim(String $string, $additionalChars = false)
   {
       
   }

   public function padLeft(String $string, $length, $padString = ' ')
   {
       
   }

   public function padRight(String $string, $length, $padString = ' ')
   {
       
   }

   public function rightTrim(String $string, $additionalChars = false)
   {
       
   }

   public function trim(String $string, $additionalChars = false)
   {
       
   }

}
