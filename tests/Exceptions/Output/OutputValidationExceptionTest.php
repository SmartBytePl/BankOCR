<?php

use BankOCR\Exceptions\Output\OutputValidationException;
use PHPUnit\Framework\TestCase;

class OutputValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new OutputValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('NAN', $result);
    }
}
