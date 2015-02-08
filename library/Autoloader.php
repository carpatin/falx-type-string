<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Autoloader
{

    static function autoload($className)
    {
        // Replacing \ in class' fully qualified name with DIRECTORY_SEPARATOR
        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) .
                '.php';
        // Extra check that the script file is actually reachable
        //(this is optional)
        if (!self::scriptExistsInIncludePath($filePath)) {
            // By returning false PHP knows that this autoloader wasn't able
            // to load the class
            return false;
        }
        // Require the script file that contains the class' definition
        require $filePath;
    }

    /**
     * Checks if the given file exists in current include path.
     * @param string $filePath
     * @return boolean
     */
    private static function scriptExistsInIncludePath($filePath)
    {
        $filePaths = explode(PATH_SEPARATOR, get_include_path());
        $found = false;
        foreach ($filePaths as $filePathPrefix) {
            $absolutePath = $filePathPrefix . DIRECTORY_SEPARATOR . $filePath;
            if (file_exists($absolutePath)) {

                $found = true;
                break;
            }
        }
        return $found;
    }

    /**
     * Registers the autoload function
     */
    static function register()
    {
        spl_autoload_register('Autoloader::autoload');
    }

}
