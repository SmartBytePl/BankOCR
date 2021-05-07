<?php

use BankOCR\Exceptions\Output\ChecksumOutputValidationException;
use PHPUnit\Framework\TestCase;

class ChecksumValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new ChecksumOutputValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('ERR', $result);
    }
}
