<?php

use BankOCR\Validators\ChecksumValidator;
use PHPUnit\Framework\TestCase;
use BankOCR\Exceptions\ValidationException;

class ChecksumValidatorTest extends TestCase
{
    private const CORRECT_INPUT_CHECKSUM = '457508000';
    private const CORRECT_INPUT_CHECKSUM2 = '345882865';
    private const NOT_CORRECT_INPUT_CHECKSUM = '345882860';

    /** @var ChecksumValidator */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->sut = new ChecksumValidator();
    }

    /**
     * @test
     */
    public function testAssertIsValidShouldReturnVoid()
    {
        $result = $this->sut->assertIsValid(self::CORRECT_INPUT_CHECKSUM);

        $this->assertNull($result);

        $result = $this->sut->assertIsValid(self::CORRECT_INPUT_CHECKSUM2);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function testAssertIsValidShouldThrowException()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Checksum validation error.');

        $this->sut->assertIsValid(self::NOT_CORRECT_INPUT_CHECKSUM);
    }
}
