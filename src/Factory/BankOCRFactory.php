<?php

declare(strict_types=1);

namespace BankOCR\Factory;

use BankOCR\BankOCR;
use BankOCR\Parser;
use BankOCR\Dictionary\DigitsSignaturesDictionary;
use BankOCR\Validators\Output\ChecksumOutputValidator;
use BankOCR\Validators\Output\FormatOutputValidator;
use BankOCR\Validators\Input\HasLinesCountValidator;
use BankOCR\Validators\Input\HasLinesLengthsValidator;

class BankOCRFactory
{
    /**
     * @return BankOCR
     */
    public function createBankAccountOCR(): BankOCR
    {
        $dictionary = new DigitsSignaturesDictionary();
        $parser = new Parser($dictionary);

        $inputValidators = [
            new HasLinesCountValidator(),
            new HasLinesLengthsValidator(),
        ];

        $outputValidators = [
            new FormatOutputValidator(),
            new ChecksumOutputValidator(),
        ];

        return new BankOCR($parser, $inputValidators, $outputValidators);
    }
}
