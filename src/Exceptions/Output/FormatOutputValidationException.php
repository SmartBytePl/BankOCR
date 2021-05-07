<?php

declare(strict_types=1);

namespace BankOCR\Exceptions\Output;

class FormatOutputValidationException extends OutputValidationException
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
