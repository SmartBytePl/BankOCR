<?php

declare(strict_types=1);

namespace BankOCR;

use BankOCR\Dictionary\DictionaryInterface;
use \RuntimeException;

class Parser
{
    /** @var int */
    private $digitsCount;

    /** @var int */
    private $digitWidth;

    /** @var int */
    private $digitHeight;

    /** @var DictionaryInterface */
    private $dictionary;

    /**
     * @param DictionaryInterface $dictionary
     * @param int $digitsCount
     * @param int $digitWidth
     * @param int $digitHeight
     */
    public function __construct(DictionaryInterface $dictionary, int $digitsCount = 9, int $digitWidth = 3, int $digitHeight = 3)
    {
        $this->dictionary = $dictionary;
        $this->digitsCount = $digitsCount;
        $this->digitWidth = $digitWidth;
        $this->digitHeight = $digitHeight;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function parse(string $input): string
    {
        $result = '';
        for ($digitId = 0; $digitId < $this->digitsCount; $digitId++) {
            $sourceSignature = $this->extractSourceSignature($input, $digitId);
            $result .= $this->dictionary->match($sourceSignature);
        }

        return $result;
    }

    /**
     * @param string $input
     * @param int $signatureIndex
     *
     * @return string
     * @throws RuntimeException
     */
    public function extractSourceSignature(string $input, int $signatureIndex): string
    {
        if ($signatureIndex < 0 || $signatureIndex > $this->digitsCount - 1) {
            throw new RuntimeException('Signature index out of range');
        }

        $result = [];
        $startPosition = $this->digitWidth * $signatureIndex;
        $lines = explode(PHP_EOL, $input);
        for ($line = 0; $line < $this->digitHeight; $line++) {
            $result[] = substr($lines[$line], $startPosition, $this->digitWidth);
        }

        return implode(PHP_EOL, $result);
    }
}
