<?php

use PHPUnit\Framework\TestCase;
use BankOCR\Validators\Input\HasLinesCountValidator;
use BankOCR\Exceptions\Input\LinesCountValidationException;

class HasLinesCountValidatorTest extends TestCase
{
    /** @var HasLinesCountValidator */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->sut = new HasLinesCountValidator(4);
    }

    /**
     * @test
     */
    public function testShouldReturnNullWhenInputHaveFourLines(): void
    {
        $input = PHP_EOL.' '.PHP_EOL.' '.PHP_EOL.' ';

        $result = $this->sut->assertIsValid($input);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenInputDoesNotHaveFourLines(): void
    {
        $input = '';

        $this->expectException(LinesCountValidationException::class);
        $this->expectExceptionMessage('Input should have exactly 4 lines, but contains 1');

        $this->sut->assertIsValid($input);
    }
}
