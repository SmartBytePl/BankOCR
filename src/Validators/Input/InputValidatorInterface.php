<?php

namespace BankOCR\Validators\Input;

use BankOCR\Exceptions\Output\OutputValidationException;

interface InputValidatorInterface
{
    /**
     * @param string $string
     *
     * @return void
     * @throws OutputValidationException
     */
    public function assertIsValid(string $string): void;
}
