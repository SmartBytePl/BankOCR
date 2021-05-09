<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BankOCR\Parser;
use BankOCR\Dictionary\DictionaryAbstract;
use PHPUnit\Framework\MockObject\MockObject;


class ParserTest extends TestCase
{
    private const ENTRIES_PATH = __DIR__.DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'entries';
    private const SIMILAR_INPUT =
        '    _  _     _  _  _  _  _ '.PHP_EOL.
        '  | _| _||_| _ |_   ||_||_|'.PHP_EOL.
        '  ||_  _|  | _||_|  ||_| _ ';

    /** @var DictionaryAbstract|MockObject */
    private $dictionaryMock;

    /** @var Parser */
    private $sut;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->dictionaryMock = $this->createMock(DictionaryAbstract::class);
        $this->dictionaryMock
            ->expects($this->any())
            ->method('match')
            ->willReturn('5');

        $this->sut = new Parser($this->dictionaryMock);
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenSignatureIndexOutOfRange()
    {
        $input = $this->loadFixture('123456789');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Signature index out of range');

        $this->sut->extractSourceSignature($input, 9);
    }

    /**
     * @test
     */
    public function testShouldThrowExceptionWhenSignatureIndexIsNegative()
    {
        $input = $this->loadFixture('123456789');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Signature index out of range');

        $this->sut->extractSourceSignature($input, -1);
    }

    /**
     * @test
     */
    public function testShouldExtractDigitInputData()
    {
        $input = $this->loadFixture('123456789');
        $result = $this->sut->extractSourceSignature($input, 8);
        $expectedNine= ' _ '.PHP_EOL.'|_|'.PHP_EOL.' _|';

        $this->assertSame($expectedNine, $result);

        $result = $this->sut->extractSourceSignature($input, 0);
        $expectedOne = '   '.PHP_EOL.'  |'.PHP_EOL.'  |';

        $this->assertSame($expectedOne, $result);
    }

    /**
     * @test
     */
    public function testShouldReturnSimilarInputData()
    {
        $this->dictionaryMock
            ->expects($this->exactly(9))
            ->method('findSimilar')
            ->willReturn(['6']);

        $expected = [
            '655555555',
            '565555555',
            '556555555',
            '555655555',
            '555565555',
            '555556555',
            '555555655',
            '555555565',
            '555555556',
        ];

        $result = $this->sut->parseSimilar(self::SIMILAR_INPUT);
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function testShouldReadInputData()
    {
        $input = $this->loadFixture('123456789');
        $result = $this->sut->parse($input);

        $this->assertSame('555555555', $result);
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private function loadFixture($filename): string
    {
        return file_get_contents(self::ENTRIES_PATH.DIRECTORY_SEPARATOR.$filename);
    }
}
