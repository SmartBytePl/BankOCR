<?php

declare(strict_types=1);

namespace BankOCR;

use BankOCR\Dictionary\DictionaryInterface;
use \RuntimeException;

class Parser
{
    /** @var int */
    private $entryLinesCount;

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
     * @param int $entryLinesCount
     */
    public function __construct(DictionaryInterface $dictionary, int $digitsCount = 9, int $digitWidth = 3, int $digitHeight = 3, int $entryLinesCount = 4)
    {
        $this->dictionary = $dictionary;
        $this->digitsCount = $digitsCount;
        $this->digitWidth = $digitWidth;
        $this->digitHeight = $digitHeight;
        $this->entryLinesCount = $entryLinesCount;
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
     * @param int $distance
     *
     * @return array|string[]
     */
    public function parseSimilar(string $input, int $distance = 1): array
    {
        $alternatives = [];
        $baseResult = [];
        for ($digitId = 0; $digitId < $this->digitsCount; $digitId++) {
            $sourceSignature = $this->extractSourceSignature($input, $digitId);
            $baseResult[$digitId] = $this->dictionary->match($sourceSignature);
            $alternatives[$digitId] = $this->dictionary->findSimilar($sourceSignature, $distance);
        }

        return $this->buildAlternatives($alternatives, $baseResult);
    }

    /**
     * @param string $bulkInput
     * @return array|string[]
     */
    public function splitBulkInput(string $bulkInput): array
    {
        $inputArray = explode(PHP_EOL, $bulkInput);
        $chunks = array_chunk($inputArray, $this->entryLinesCount);

        $inputs = [];
        foreach ($chunks as $chunk) {
            if (count($chunk) < $this->entryLinesCount) {
                continue;
            }
            $results[] = implode(PHP_EOL, $chunk);
        }

        return $inputs;
    }


    /**
     * @param array $alternatives
     * @param array $baseResult
     * @return array
     */
    private function buildAlternatives(array $alternatives, array $baseResult): array
    {
        $results = [];
        foreach ($alternatives as $digitId => $similarDigits) {
            $alternative = $baseResult;
            foreach ($similarDigits as $similarDigit) {
                $alternative[$digitId] = $similarDigit;
                $results[] = implode('', $alternative);
            }
        }

        return $results === [] ? [$baseResult] : $results;
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
