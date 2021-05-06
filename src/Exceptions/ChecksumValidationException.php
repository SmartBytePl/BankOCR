<?php

declare(strict_types=1);

namespace BankOCR\Exceptions;

class ChecksumValidationException extends ValidationException
{
    private const EXCEPTION_SYMBOL = 'ERR';

    /** {@inheritDoc} */
    public function __construct($message = "", $code = 0, ?Throwable $previous = null) {
        parent::__construct('Checksum validation error.', $code, $previous);
    }

    /**
     * {@inheritDoc}
     */
    public function getSymbol(): string
    {
        return self::EXCEPTION_SYMBOL;
    }
}
