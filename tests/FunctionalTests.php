<?php

use BankOCR\Dictionary\DigitsSignaturesDictionary;
use BankOCR\Factory\BankOCRFactory;
use BankOCR\BankOCR;
use BankOCR\Parser;
use BankOCR\Validators\Input\HasLinesCountValidator;
use BankOCR\Validators\Input\HasLinesLengthsValidator;
use PHPUnit\Framework\TestCase;

class FunctionalTests extends TestCase
{
    private const FIXTURES_PATH = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR;
    private const ENTRIES_PATH = self::FIXTURES_PATH.'entries'.DIRECTORY_SEPARATOR;

    /** @var BankOCR */
    private $sut;

    protected function setUp(): void
    {
        $dictionary = new DigitsSignaturesDictionary();
        $parser = new Parser($dictionary);

        $inputValidators = [
            new HasLinesCountValidator(),
            new HasLinesLengthsValidator(),
        ];

        $outputValidators = [];

        $this->sut = new BankOCR($parser, $inputValidators, $outputValidators);
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
        $result = $this->sut->recognize($input);
        $resultAsString = implode(PHP_EOL, $result);

        $this->assertSame($expected, $resultAsString);
    }

    /**
     * @test
     */
    public function testItShouldProcessUseCase1()
    {
        $bulkInput = file_get_contents(self::FIXTURES_PATH.'use_case_1_in.txt');
        $bulkOutput = file_get_contents(self::FIXTURES_PATH.'use_case_1_out.txt');

        $result = $this->sut->recognize($bulkInput);
        $resultAsString = implode(PHP_EOL, $result).PHP_EOL;

        $this->assertSame($bulkOutput, $resultAsString);
    }

    /**
     * @test
     */
    public function testItShouldProcessUseCase3()
    {
        $bulkInput = file_get_contents(self::FIXTURES_PATH.'use_case_3_in.txt');
        $bulkOutput = file_get_contents(self::FIXTURES_PATH.'use_case_3_out.txt');

        $this->sut = (new BankOCRFactory())->createBankAccountOCR();
        $result = $this->sut->recognize($bulkInput);
        $resultAsString = implode(PHP_EOL, $result).PHP_EOL;

        $this->assertSame($bulkOutput, $resultAsString);
    }

    /**
     * @return array
     */
    public function entriesProvider(): array
    {
        $files = array_diff(scandir(self::ENTRIES_PATH), array('..', '.'));

        $result = [];
        foreach ($files as $filename) {
            $result [] = [$filename, $this->loadEntriesFixture($filename)];
        }

        return $result;
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private function loadEntriesFixture($filename): string
    {
        return file_get_contents(self::ENTRIES_PATH.$filename);
    }
}
