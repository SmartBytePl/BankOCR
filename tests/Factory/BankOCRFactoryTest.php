<?php

use BankOCR\BankOCR;
use BankOCR\Factory\BankOCRFactory;
use PHPUnit\Framework\TestCase;

class BankOCRFactoryTest extends TestCase
{
    /** @var BankOCRFactory */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = new BankOCRFactory();
    }

    public function testItShouldCreateBankOCR()
    {
        $result = $this->sut->createBankAccountOCR();

        $this->assertInstanceOf(BankOCR::class, $result);
    }

}
