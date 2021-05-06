<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BankOCR\EntryParser;
use BankOCR\Exceptions\EntryParserException;


class EntryParserTest extends TestCase
{
    private const ENTRIES_PATH = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'entries';

    /** @var EntryParser */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->sut = new EntryParser();
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
        $result = $this->sut->parse($input);

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenInputDoesNotHaveFourLines(): void
    {
        $input = '';

        $this->expectException(EntryParserException::class);
        $this->expectExceptionMessage('Input should have exactly 4 lines, but contains 1');

        $this->sut->parse($input);
    }

    /**
     * @test
     * @param string $input
     * @param string $message
     *
     * @dataProvider wrongLinesLengthProvider
     */
    public function testShouldThrowExceptionWhenInputDoesNotHaveExpectedLineLengths(string $input, string $message): void
    {
        $this->expectException(EntryParserException::class);
        $this->expectExceptionMessage($message);

        $this->sut->parse($input);
    }

    /**
     * @return array
     */
    public function wrongLinesLengthProvider(): array
    {
        return [
            'first line is 5 length' => [
                'line1'.PHP_EOL.
                'line2'.PHP_EOL.
                'line3'.PHP_EOL.
                'line4',
                'Line 0 should have exactly 27 characters, but contains 5'
            ],
            'second line is 5 length' => [
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                'line2'.PHP_EOL.
                'line3'.PHP_EOL.
                'line4',
                'Line 1 should have exactly 27 characters, but contains 5'
            ],
            'third line is 5 length' => [
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                'line2'.PHP_EOL.
                'line4',
                'Line 2 should have exactly 27 characters, but contains 5'
            ],
            'fourth line is 27 length' => [
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
                '0123456789ABCDEFGHIJKLMNOPR',
                'Line 3 should have exactly 0 characters, but contains 27'
            ]

        ];
    }

    /**
     * @return array
     */
    public function entriesProvider(): array
    {
        $files = array_diff(scandir(self::ENTRIES_PATH), array('..', '.'));

        $result = [];
        foreach ($files as $file) {
            $result [] = [$file, file_get_contents(self::ENTRIES_PATH.DIRECTORY_SEPARATOR.$file)];
        }

        return $result;
    }
}
