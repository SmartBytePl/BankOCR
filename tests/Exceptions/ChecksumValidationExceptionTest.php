<?php

use BankOCR\Exceptions\ChecksumValidationException;
use PHPUnit\Framework\TestCase;

class ChecksumValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new ChecksumValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('ERR', $result);
    }
}
