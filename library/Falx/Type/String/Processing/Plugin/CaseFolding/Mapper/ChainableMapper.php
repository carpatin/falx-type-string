<?php

/*
 * This file is part of the Falx PHP library.
 *
 * (c) Dan Homorodean <dan.homorodean@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Falx\Type\String\Processing\Plugin\CaseFolding\Mapper;

use Falx\Type\String\Processing\Util\Unicode;

/**
 * Abstract class, base class for chainable case folding mappers.
 * @author Dan Homorodean <dan.homorodean@gmail.com>
 */
abstract class ChainableMapper
{
    /*
     * Mapping modes
     */

    const MODE_LOWER_TO_UPPER = 1;
    const MODE_UPPER_TO_LOWER = 2;

    /**
     * Current mapping mode
     * @var int 
     */
    protected $mode = self::MODE_LOWER_TO_UPPER;

    /*
     * Folding types 
     */

    const FOLDING_TYPE_SIMPLE = 1;
    const FOLDING_TYPE_FULL = 2;

    /**
     * The folding type (the rules) used
     * @var int
     */
    protected $foldingType = self::FOLDING_TYPE_SIMPLE;

    /**
     * Whether the Turkic folding rules are applied (before any other rules).
     * @var boolean
     */
    protected $applyTurkicFolding = false;

    /**
     * The next mapper in chain
     * @var ChainableMapper 
     */
    private $next;

    /*
     * Mapping containers, differentiated by mapping statuses
     */
    protected $mappingsLoaded;
    protected $commonMappingsToUpper;
    protected $commonMappingsToLower;
    protected $fullMapingsToUpper;
    protected $fullMapingsToLower;
    protected $simpleMappingsToUpper;
    protected $simpleMappingsToLower;
    protected $turkicMappingsToUpper;
    protected $turkicMappingsToLower;

    /**
     * Characters ignored from case folding.
     * @var array 
     */
    protected $ignoredCharacters = [
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ' ', '.', ',', ';', ':', '?', '!', '\'', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '<', '>', '(', ')', '_', '-', '+', '=', '/', '{', '}', '[', ']', '|', '\\'
    ];

    /**
     * Initializes mapping data structures
     */
    public function __construct()
    {
        $this->commonMappingsToUpper = [];
        $this->commonMappingsToLower = [];
        $this->fullMapingsToUpper = [];
        $this->fullMapingsToLower = [];
        $this->simpleMappingsToUpper = [];
        $this->simpleMappingsToLower = [];
        $this->turkicMappingsToUpper = [];
        $this->turkicMappingsToLower = [];
        $this->mappingsLoaded = false;
    }

    /**
     * Sets the nest mapper in chain.
     * @param ChainableMapper $next
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function setNext(ChainableMapper $next)
    {
        $this->next = $next;
    }

    /**
     * Sets the mapping mode.
     * @param int $mode
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Seta the folding type.
     * See FOLDING_TYPE_* constants for available options.
     * @param int $foldingType
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    function setFoldingType($foldingType)
    {
        $this->foldingType = $foldingType;
    }

    /**
     * Sets the flag for applying Turkic folding rules.
     * @param boolean $applyTurkicFolding
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    function setApplyTurkicFolding($applyTurkicFolding)
    {
        $this->applyTurkicFolding = $applyTurkicFolding;
    }

    /**
     * Mapped character lookup method, falls back to the rest of the chain if cannot resolve itself the mapping.
     * @param string $character
     * @return string|false
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    public function lookup($character)
    {
        // Try to map the character
        $result = $this->map($character);

        // If cannot map the character then pass the responsibility to the next in chain (if any)
        if ($result === false && $this->next !== null) {
            $this->next->setMode($this->mode);
            return $this->next->lookup($character);
        }

        return $result;
    }

    /**
     * Maps a UTF-8 represented character to the lowercase/uppercase version of it,
     * based on the currently set mode.
     * @param string $character
     * @return string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    protected function map($character)
    {
        $this->ensureMappingsLoaded();

        // Test if one of the ignored characters (avoid chaining for those characters)
        if (in_array($character, $this->ignoredCharacters)) {
            return $character;
        }
        // Check if already mapped, if so then return the character unchanged
        // (to avoid chaining for those characters)
        if ($this->isAlreadyCorrectCase($character)) {
            return $character;
        }

        // Important to return false if cannot map with current mapper.
        $mapped = false;
        // Do the actual mapping
        switch ($this->mode) {

            /*
             * Mapping mode is uppercase -> lowercase
             */
            case self::MODE_UPPER_TO_LOWER:

                // Test if doing also Turkic characters folding ( T )
                if ($this->applyTurkicFolding && array_key_exists($character, $this->turkicMappingsToLower)) {
                    $mapped = $this->turkicMappingsToLower[$character];
                } else {
                    switch ($this->foldingType) {
                        /*
                         * Folding type is simple ( C + S )
                         */
                        case self::FOLDING_TYPE_SIMPLE:
                            if (array_key_exists($character, $this->commonMappingsToLower)) {
                                $mapped = $this->commonMappingsToLower[$character];
                            } elseif (array_key_exists($character, $this->simpleMappingsToLower)) {
                                $mapped = $this->simpleMappingsToLower[$character];
                            }
                            break;
                        /*
                         * Folding type is full ( C + F )
                         */
                        case self::FOLDING_TYPE_FULL:
                            if (array_key_exists($character, $this->commonMappingsToLower)) {
                                $mapped = $this->commonMappingsToLower[$character];
                            } elseif (array_key_exists($character, $this->fullMapingsToLower)) {
                                $mapped = $this->fullMapingsToLower[$character];
                            }
                            break;
                    }
                }

                break;

            /*
             * Mapping mode is lowercase -> uppercase
             */
            case self::MODE_LOWER_TO_UPPER:

                if ($this->applyTurkicFolding && array_key_exists($character, $this->turkicMappingsToUpper)) {
                    $mapped = $this->turkicMappingsToUpper[$character];
                } else {
                    switch ($this->foldingType) {
                        /*
                         * Folding type is simple ( C + S )
                         */
                        case self::FOLDING_TYPE_SIMPLE:
                            if (array_key_exists($character, $this->commonMappingsToUpper)) {
                                $mapped = $this->commonMappingsToUpper[$character];
                            } elseif (array_key_exists($character, $this->simpleMappingsToUpper)) {
                                $mapped = $this->simpleMappingsToUpper[$character];
                            }
                            break;
                        /*
                         * Folding type is full ( C + F )
                         */
                        case self::FOLDING_TYPE_FULL:
                            if (array_key_exists($character, $this->commonMappingsToUpper)) {
                                $mapped = $this->commonMappingsToUpper[$character];
                            } elseif (array_key_exists($character, $this->fullMapingsToUpper)) {
                                $mapped = $this->fullMapingsToUpper[$character];
                            }
                            break;
                    }
                }
                break;
        }

        return $mapped;
    }

    /**
     * Checks if the given character is already in the required case.
     * @param string $character
     * @return boolean
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    private function isAlreadyCorrectCase($character)
    {
        $alreadyMapped = false;
        switch ($this->mode) {
            case self::MODE_UPPER_TO_LOWER:
                $alreadyMapped = false;
                if ($this->applyTurkicFolding) {
                    $alreadyMapped |= array_key_exists($character, $this->turkicMappingsToUpper);
                }

                switch ($this->foldingType) {
                    case self::FOLDING_TYPE_SIMPLE:
                        $alreadyMapped |= array_key_exists($character, $this->commonMappingsToUpper) ||
                                array_key_exists($character, $this->simpleMappingsToUpper);
                        break;
                    case self::FOLDING_TYPE_FULL:
                        $alreadyMapped |= array_key_exists($character, $this->commonMappingsToUpper) ||
                                array_key_exists($character, $this->fullMapingsToUpper);
                        break;
                }

                break;
            case self::MODE_LOWER_TO_UPPER:
                $alreadyMapped = false;
                if ($this->applyTurkicFolding) {
                    $alreadyMapped |= array_key_exists($character, $this->turkicMappingsToLower);
                }

                switch ($this->foldingType) {
                    case self::FOLDING_TYPE_SIMPLE:
                        $alreadyMapped |= array_key_exists($character, $this->commonMappingsToLower) ||
                                array_key_exists($character, $this->simpleMappingsToLower);
                        break;
                    case self::FOLDING_TYPE_FULL:
                        $alreadyMapped |= array_key_exists($character, $this->commonMappingsToLower) ||
                                array_key_exists($character, $this->fullMapingsToLower);
                        break;
                }

                break;
        }
        return $alreadyMapped;
    }

    /**
     * Transforms one ore more Unicode codepoints given in HEX representation to 
     * the corresponding UTF-8 string.
     * @param string $codes Codepoints given in HEX representation, separated by spaces.
     * @return string UTF-8 characters string
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    protected function codepointsToCharacters($codes)
    {
        $chars = '';
        $codePoints = explode(' ', $codes);
        foreach ($codePoints as $codePoint) {
            $charBytes = Unicode::hexCodepointToUTF8CharacterBytes($codePoint);
            foreach ($charBytes as $charByte) {
                $chars.=$charByte;
            }
        }
        return $chars;
    }

    /**
     * Ensures mappings are loaded into object.
     * @author Dan Homorodean <dan.homorodean@gmail.com>
     */
    protected function ensureMappingsLoaded()
    {
        if ($this->mappingsLoaded === false) {

            $filename = $this->getMappingsFilename();

            $file = new \SplFileObject($filename);
            $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

            foreach ($file as $rule) {
                list($from, $to, $status) = $rule;
                $fromChar = $this->codepointsToCharacters($from);
                $toChar = $this->codepointsToCharacters($to);

                switch ($status) {
                    case 'C':
                        // Avoiding setting another value instead of a previously set one for a given key!
                        if (!array_key_exists($fromChar, $this->commonMappingsToLower)) {
                            $this->commonMappingsToLower[$fromChar] = $toChar;
                        }
                        if (!array_key_exists($toChar, $this->commonMappingsToUpper)) {
                            $this->commonMappingsToUpper[$toChar] = $fromChar;
                        }
                        break;
                    case 'F':
                        $this->fullMapingsToLower[$fromChar] = $toChar;
                        $this->fullMapingsToUpper[$toChar] = $fromChar;
                        break;
                    case 'S':
                        $this->simpleMappingsToLower[$fromChar] = $toChar;
                        $this->simpleMappingsToUpper[$toChar] = $fromChar;
                        break;
                    case 'T':
                        $this->turkicMappingsToLower[$fromChar] = $toChar;
                        $this->turkicMappingsToUpper[$toChar] = $fromChar;
                        break;
                }
            }
            $this->mappingsLoaded = true;
        }
    }

    /**
     * Returns the filename the mapper uses as mappings data source.
     */
    abstract protected function getMappingsFilename();
}
