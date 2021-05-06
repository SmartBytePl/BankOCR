<?php

use BankOCR\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;

class ValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new ValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('NAN', $result);
    }
}
