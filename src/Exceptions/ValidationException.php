<?php

declare(strict_types=1);

namespace BankOCR\Exceptions;

use \InvalidArgumentException;

class ValidationException extends InvalidArgumentException
{
    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return 'NAN';
    }
}
