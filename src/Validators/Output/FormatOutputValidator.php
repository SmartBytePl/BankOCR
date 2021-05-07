<?php

namespace BankOCR\Validators\Output;

use BankOCR\Exceptions\Output\FormatOutputValidationException;

class FormatOutputValidator implements OutputValidatorInterface
{
    /** @var string */
    private $matchExpression;

    /**
     * @param string $matchExpression
     */
    public function __construct(string $matchExpression)
    {
        $this->matchExpression = $matchExpression;
    }

    /** {@inheritDoc} */
    public function assertIsValid(string $input): void {
        if (!(bool) preg_match($this->matchExpression, $input)) {
            throw new FormatOutputValidationException('Incorrect string format, expecting: '.$this->matchExpression);
        }
    }
}
