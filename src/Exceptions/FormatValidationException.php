<?php

declare(strict_types=1);

namespace BankOCR\Exceptions;

class FormatValidationException extends ValidationException
{
    private const EXCEPTION_SYMBOL = 'ILL';

    /**
     * {@inheritDoc}
     */
    public function getSymbol(): string
    {
        return self::EXCEPTION_SYMBOL;
    }
}
