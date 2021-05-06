<?php

namespace BankOCR\Validators;

use BankOCR\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * @param string $string
     *
     * @throws ValidationException
     * @return void
     */
    public function assertIsValid(string $string): void;
}
