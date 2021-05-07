<?php

declare(strict_types=1);

namespace BankOCR\Validators\Input;

use BankOCR\Exceptions\Input\LinesCountValidationException;

class HasLinesCountValidator implements InputValidatorInterface
{
    private const LINES_NUMBER_EXCEPTION_MESSAGE = 'Input should have exactly %d lines, but contains %d';

    /** @var int */
    private $linesCount;

    /**
     * @param int $linesCount
     */
    public function __construct(int $linesCount = 4)
    {
        $this->linesCount = $linesCount;
    }


    /** {@inheritDoc} */
    public function assertIsValid(string $input): void
    {
        $linesCount = count(explode(PHP_EOL, $input));
        if ($this->linesCount !== $linesCount) {
            throw new LinesCountValidationException(
                sprintf(self::LINES_NUMBER_EXCEPTION_MESSAGE, $this->linesCount, $linesCount)
            );
        }
    }
}
