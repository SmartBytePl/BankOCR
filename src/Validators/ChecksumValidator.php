<?php

declare(strict_types=1);

namespace BankOCR\Validators;

use BankOCR\Exceptions\ChecksumValidationException;

class ChecksumValidator
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
            throw new ChecksumValidationException();
        }
    }
}
