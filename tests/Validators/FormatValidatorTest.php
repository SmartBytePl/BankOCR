<?php

use BankOCR\Validators\FormatValidator;
use PHPUnit\Framework\TestCase;
use BankOCR\Exceptions\ValidationException;

class FormatValidatorTest extends TestCase
{

    /** @var FormatValidator */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->sut = new FormatValidator();
    }

    /**
     * @test
     * @param string $input
     * @dataProvider correctInputProvider
     */
    public function testAssertIsValidShouldReturnVoid(string $input)
    {
        $result = $this->sut->assertIsValid($input);

        $this->assertNull($result);
    }

    /**
     * @test
     * @param string $input
     * @dataProvider wrongInputProvider
     */
    public function testAssertIsValidShouldThrowException(string $input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Incorrect string format, expecting: /^[0-9]{9}$/');

        $this->sut->assertIsValid($input);
    }

    /**
     * @return array|string[]
     */
    public function correctInputProvider(): array
    {
        return [
          ['123456789'],
          ['023456789'],
          ['000000000'],
          ['111111111'],
          ['222222222'],
          ['333333333'],
        ];
    }

    /**
     * @return array|string[]
     */
    public function wrongInputProvider(): array
    {
        return [
            [''],
            ['12345678'],
            ['1234567890'],
            ['12345678?'],
            ['?23456789'],
            ['ABCDEFGHI'],
            ['---------'],
        ];
    }
}
