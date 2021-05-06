<?php

namespace BankOCR\Validators;

use BankOCR\Exceptions\FormatValidationException;

class FormatValidator implements ValidatorInterface
{
    private const MATCH_EXPRESSION = '/^[0-9]{9}$/';
    private const EXCEPTION_SIGNATURE = 'ILL';

    /** {@inheritDoc} */
    public function assertIsValid(string $input): void {
        if (!(bool) preg_match(self::MATCH_EXPRESSION, $input)) {
            throw new FormatValidationException('Incorrect string format, expecting: '.self::MATCH_EXPRESSION);
        }
    }


}
