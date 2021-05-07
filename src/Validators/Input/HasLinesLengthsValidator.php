<?php

declare(strict_types=1);

namespace BankOCR\Validators\Input;

use BankOCR\Exceptions\Input\LinesLengthsValidationException;

class HasLinesLengthsValidator implements InputValidatorInterface
{
    private const LINES_LENGTH_EXCEPTION_MESSAGE = 'Line %d should have exactly %d characters, but contains %d';

    /** @var array<int, int> */
    private $lineLengths;

    /**
     * @param array $lineLengths
     */
    public function __construct(array $lineLengths = [27, 27, 27])
    {
        $this->lineLengths = $lineLengths;
    }

    /** {@inheritDoc} */
    public function assertIsValid(string $input): void
    {
        foreach (explode(PHP_EOL, $input) as $key => $line) {
            $length = strlen($line);
            if (isset($this->lineLengths[$key]) && $length !== $this->lineLengths[$key]) {
                throw new LinesLengthsValidationException(
                    sprintf(self::LINES_LENGTH_EXCEPTION_MESSAGE, $key, $this->lineLengths[$key], $length)
                );
            }
        }
    }
}
