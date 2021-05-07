<?php

namespace BankOCR\Validators\Output;

use BankOCR\Exceptions\Output\OutputValidationException;

interface OutputValidatorInterface
{
    /**
     * @param string $string
     *
     * @return void
     * @throws OutputValidationException
     */
    public function assertIsValid(string $string): void;
}
