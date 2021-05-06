<?php

declare(strict_types=1);

namespace BankOCR;

use BankOCR\Exceptions\EntryParserException;
use BankOCR\Enum\DigitsSignaturesEnumInterface;

class EntryParser
{
    public const LINE_DELIMITER = PHP_EOL;
    private const LINES_NUMBER = 4;
    private const LINE_LENGTHS = [27, 27, 27, 0];
    private const DIGITS_NUMBER = 9;
    private const DIGIT_WIDTH = 3;

    private const LINES_NUMBER_EXCEPTION_MESSAGE = 'Input should have exactly %d lines, but contains %d';
    private const LINES_LENGTH_EXCEPTION_MESSAGE = 'Line %d should have exactly %d characters, but contains %d';


    /**
     * @param string $input
     *
     * @return string
     */
    public function parse(string $input): string
    {
        $this->assertHasExpectedLinesNumber($input);
        $this->assertHasExpectedLinesLength($input);

        $result = '';
        for ($digitId = 0; $digitId < self::DIGITS_NUMBER; $digitId++) {
            $digitSource = $this->extractDigitSource($input, $digitId);
            $result .= $this->recognizeDigitSource($digitSource);
        }

        return $result;
    }

    /**
     * @param string $digitSource
     *
     * @return string
     */
    private function recognizeDigitSource(string $digitSource): string
    {
        foreach (DigitsSignaturesEnumInterface::SIGNATURES as $key => $signature) {
            if ($digitSource === $signature) {
                return (string) $key;
            }
        }

        return '?';
    }

    /**
     * @param string $input
     * @param int $position
     *
     * @return string
     */
    private function extractDigitSource(string $input, int $position): string
    {
        $result = [];
        $startPosition = self::DIGIT_WIDTH * $position;
        $lines = $this->explodeLines($input);
        for ($line = 0; $line < self::LINES_NUMBER - 1; $line++) {
            $result[] = substr($lines[$line], $startPosition, self::DIGIT_WIDTH);
        }

        return implode(self::LINE_DELIMITER, $result);
    }

    /**
     * @param string $input
     */
    private function assertHasExpectedLinesNumber(string $input): void
    {
        $linesCount = count($this->explodeLines($input));
        if (self::LINES_NUMBER !== $linesCount) {
            throw new EntryParserException(
                sprintf(self::LINES_NUMBER_EXCEPTION_MESSAGE, self::LINES_NUMBER, $linesCount)
            );
        }
    }

    /**
     * @param string $input
     */
    private function assertHasExpectedLinesLength(string $input): void
    {
        foreach ($this->explodeLines($input) as $key => $line) {
            $length = strlen($line);
            if ($length !== self::LINE_LENGTHS[$key]) {
                throw new EntryParserException(
                    sprintf(self::LINES_LENGTH_EXCEPTION_MESSAGE, $key, self::LINE_LENGTHS[$key], $length)
                );
            }
        }
    }

    private function explodeLines(string $input): array
    {
        return explode(self::LINE_DELIMITER, $input);
    }
}
