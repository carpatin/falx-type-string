<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Util;

/**
 * Utility class that provides character sets representiong various character classes.
 */
class CharacterClasses
{

    /**
     * Local cache array
     * @var array 
     */
    protected static $whitespaces;

    /**
     * Returns an array with UTF8 representation strings containing Unicode  whitespace characters.
     * @return array
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public static function getWhitespaceChars()
    {
        if (self::$whitespaces === null) {

            self::$whitespaces = [
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0009')), // horizontal tab
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000A')), // line feed
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000B')), // line tabulation
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000C')), // form feed
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+000D')), // carriage return
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0020')), // ASCII space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+0085')), // next line
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+00A0')), // no-break space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+1680')), // ogham space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2000')), // en quad
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2001')), // em quad
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2002')), // en space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2003')), // em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2004')), // three-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2005')), // four-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2006')), // six-per-em space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2007')), // figure space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2008')), // punctuation space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2009')), // thin space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+200A')), // hair space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2028')), // line separator
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+2029')), // paragraph separator
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+202F')), // narrow no-break space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+205F')), // medium mathematical space
                implode(Unicode::hexCodepointToUTF8CharacterBytes('U+3000')), // ideographic space
            ];
        }

        return self::$whitespaces;
    }

}
