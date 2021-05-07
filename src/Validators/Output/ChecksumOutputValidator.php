<?php

declare(strict_types=1);

namespace BankOCR\Validators\Output;

use BankOCR\Exceptions\Output\ChecksumOutputValidationException;

class ChecksumOutputValidator
{
    /** {@inheritDoc} */
    public function assertIsValid(string $input): void
    {
        $sum = 0;
        $multiplier = 9;
        foreach (str_split($input) as $key => $digit) {
            $sum += ($multiplier - $key) * (int) $digit;
        }

        if ($sum % 11 != 0) {
            throw new ChecksumOutputValidationException();
        }
    }
}
