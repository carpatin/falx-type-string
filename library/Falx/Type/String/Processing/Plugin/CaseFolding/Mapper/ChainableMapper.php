<?php

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
    protected $specialMappingsToUpper;
    protected $specialMappingsToLower;
    
    /**
     * Characters ignored from case folding.
     * @var array 
     */
    protected $ignoredCharacters = [
        ' ', '.', ',', ';', ':', '?', '!', '\'', '"', '~', '`', '@', '#', '$', '%', '^', '&', '*', '<', '>', '(', ')', '_', '-', '+', '=', '/', '{', '}', '[', ']', '|', '\\'
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
        $this->specialMappingsToUpper = [];
        $this->specialMappingsToLower = [];
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

        $mapped = false;

        // Test if one of the ignored characters (avoid chaining for those characters)
        if (in_array($character, $this->ignoredCharacters)) {
            return $character;
        }

        // Test if not already in the correct case (avoid chaining for those characters)
        $alreadyMapped = false;
        switch ($this->mode) {
            case self::MODE_UPPER_TO_LOWER:

                if (array_key_exists($character, $this->commonMappingsToUpper)) {
                    $alreadyMapped = true;
                }

                //TODO: Handle F, S and T statuses

                break;
            case self::MODE_LOWER_TO_UPPER:

                if (array_key_exists($character, $this->commonMappingsToLower)) {
                    $alreadyMapped = true;
                }

                //TODO: Handle F, S and T statuses

                break;
        }

        if ($alreadyMapped) {
            return $character;
        }

        switch ($this->mode) {
            case self::MODE_UPPER_TO_LOWER:
                if (array_key_exists($character, $this->commonMappingsToLower)) {
                    $mapped = $this->commonMappingsToLower[$character];
                }

                //TODO: Handle F, S and T statuses

                break;
            case self::MODE_LOWER_TO_UPPER:
                if (array_key_exists($character, $this->commonMappingsToUpper)) {
                    $mapped = $this->commonMappingsToUpper[$character];
                }

                //TODO: Handle F, S and T statuses

                break;
        }

        return $mapped;
    }

    /**
     * Transforms one ore more Unicode codepoints given in HEX representation to 
     * the corresponding UTF-8 string.
     * @param string $codes Codepoints given in HEX representation, separated by spaces.
     * @return string UTF-8 characters string
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
                        $this->commonMappingsToLower[$fromChar] = $toChar;
                        $this->commonMappingsToUpper[$toChar] = $fromChar;
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
                        $this->specialMappingsToLower[$fromChar] = $toChar;
                        $this->specialMappingsToUpper[$toChar] = $fromChar;
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
