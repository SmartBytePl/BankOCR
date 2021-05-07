<?php

declare(strict_types=1);

use BankOCR\Dictionary\DigitsSignaturesDictionary;
use BankOCR\Factory\BankOCRFactory;
use BankOCR\BankOCR;
use BankOCR\Parser;
use BankOCR\Validators\Input\HasLinesCountValidator;
use BankOCR\Validators\Input\HasLinesLengthsValidator;
use BankOCR\Validators\Output\ChecksumOutputValidator;
use BankOCR\Validators\Output\FormatOutputValidator;
use PHPUnit\Framework\TestCase;

class FunctionalTests extends TestCase
{
    private const ENTRIES_PATH = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'entries';

    /** @var BankOCR */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = (new BankOCRFactory())->createBankAccountOCR();
    }

    /**
     * @test
     * @param $expected
     * @param $input
     *
     * @dataProvider entriesProvider
     */
    public function testItShouldReturnTestEntries($expected, $input)
    {
        $this->sut = $this->createBankAccountOCRWithOutOutputValidators();
        $result = $this->sut->recognize($input);

        $this->assertSame($expected, $result[0]);
    }

    /**
     * @return array
     */
    public function entriesProvider(): array
    {
        $files = array_diff(scandir(self::ENTRIES_PATH), array('..', '.'));

        $result = [];
        foreach ($files as $filename) {
            $result [] = [$filename, $this->loadFixture($filename)];
        }

        return $result;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private function loadFixture($filename): string
    {
        return file_get_contents(self::ENTRIES_PATH.DIRECTORY_SEPARATOR.$filename);
    }

    /**
     * @return BankOCR
     */
    private function createBankAccountOCRWithOutOutputValidators(): BankOCR
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

        $outputValidators = [];

        return new BankOCR($inputValidators, $outputValidators, $parser);
    }

}
