<?php

use BankOCR\Exceptions\FormatValidationException;
use PHPUnit\Framework\TestCase;

class FormatValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new FormatValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('ILL', $result);
    }
}
