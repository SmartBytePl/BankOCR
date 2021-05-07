<?php

use BankOCR\Exceptions\Output\FormatOutputValidationException;
use PHPUnit\Framework\TestCase;

class FormatValidationExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function testGetSymbol()
    {
        $sut = new FormatOutputValidationException();
        $result = $sut->getSymbol();

        $this->assertSame('ILL', $result);
    }
}
