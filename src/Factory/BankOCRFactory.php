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
        $linesLength = [27, 27, 27, 0];
        $digitsCount = 9;
        $digitWidth = 3;
        $digitHeight = 3;
        $resultRegExpMatch = '/^[0-9]{9}$/';

        $dictionary = new DigitsSignaturesDictionary();
        $parser = new Parser($dictionary, $digitsCount, $digitWidth, $digitHeight);

        $inputValidators = [
            new HasLinesCountValidator(count($linesLength)),
            new HasLinesLengthsValidator($linesLength),
        ];

        $outputValidators = [
            new FormatOutputValidator($resultRegExpMatch),
            new ChecksumOutputValidator(),
        ];

        return new BankOCR($inputValidators, $outputValidators, $parser);
    }
}
