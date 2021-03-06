<?php

use BankOCR\BankOCR;
use BankOCR\Exceptions\Input\InputValidationException;
use BankOCR\Exceptions\Output\OutputValidationException;
use BankOCR\Parser;
use BankOCR\Validators\Input\InputValidatorInterface;
use BankOCR\Validators\Output\OutputValidatorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class BankOCRTest extends TestCase
{
    /** @var Parser|MockObject */
    private $parserMock;

    /** @var BankOCR */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->parserMock = $this->createMock(Parser::class);
        $this->sut = new BankOCR($this->parserMock, [], []);
    }

    /**
     * @test
     */
    public function testItShouldProcessManyEntries()
    {
        $this->parserMock
            ->expects($this->exactly(2))
            ->method('parse')
            ->willReturn('123456789');

        $input8Lines = PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;

        $this->parserMock
            ->expects($this->once())
            ->method('splitBulkInput')
            ->willReturn([PHP_EOL.PHP_EOL.PHP_EOL, PHP_EOL.PHP_EOL.PHP_EOL]);

        $result = $this->sut->recognize($input8Lines);
        $expected = ['123456789', '123456789'];

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function testItThrowInputValidationException()
    {
        $inputValidator = $this->createMock(InputValidatorInterface::class);
        $inputValidator
            ->expects($this->once())
            ->method('assertIsValid')
            ->willThrowException(new InputValidationException());

        $this->sut = new BankOCR($this->parserMock, [$inputValidator], []);

        $this->parserMock
            ->expects($this->never())
            ->method('parse');

        $input4Lines = PHP_EOL.PHP_EOL.PHP_EOL;

        $this->parserMock
            ->expects($this->once())
            ->method('splitBulkInput')
            ->willReturn([$input4Lines]);

        $result = $this->sut->recognize($input4Lines);
        $expected = ['Skipping entry index: 0 is not valid. '];

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function testItThrowOutputValidationException()
    {
        $outputValidator = $this->createMock(OutputValidatorInterface::class);
        $outputValidator
            ->expects($this->once())
            ->method('assertIsValid')
            ->willThrowException(new OutputValidationException());

        $this->sut = new BankOCR($this->parserMock, [], [$outputValidator]);

        $this->parserMock
            ->expects($this->once())
            ->method('splitBulkInput')
            ->willReturn(['123456789']);

        $this->parserMock
            ->expects($this->once())
            ->method('parse')
            ->willReturn('123456789');

        $input4Lines = PHP_EOL.PHP_EOL.PHP_EOL;

        $result = $this->sut->recognize($input4Lines, false);
        $expected = ['123456789 NAN'];

        $this->assertSame($expected, $result);
    }
}
