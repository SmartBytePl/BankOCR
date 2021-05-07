<?php

declare(strict_types=1);

namespace BankOCR\Exceptions\Output;

use \InvalidArgumentException;

class OutputValidationException extends InvalidArgumentException
{
    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return 'NAN';
    }
}
