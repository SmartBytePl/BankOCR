<?php

use PHPUnit\Framework\TestCase;
use BankOCR\Validators\Input\HasLinesLengthsValidator;
use BankOCR\Exceptions\Input\LinesLengthsValidationException;

class HasLinesLenghtsValidatorTest extends TestCase
{
    /** @var HasLinesLengthsValidator */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->sut = new HasLinesLengthsValidator([27, 27, 27, 0]);
    }

    /**
     * @test
     */
    public function testShouldReturnNullWhenReturnHaveExpectedLineLengths(): void
    {
        $input = '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
            '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
            '0123456789ABCDEFGHIJKLMNOPR'.PHP_EOL.
            '';

        $result = $this->sut->assertIsValid($input);

        $this->assertNull($result);
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
        $this->expectException(LinesLengthsValidationException::class);
        $this->expectExceptionMessage($message);

        $this->sut->assertIsValid($input);
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
}
