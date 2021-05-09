<?php


use BankOCR\Dictionary\DigitsSignaturesDictionary;
use PHPUnit\Framework\TestCase;

class DigitsSignaturesDictionaryTest extends TestCase
{
    private const ZERO  = ' _ '.PHP_EOL.'| |'.PHP_EOL.'|_|';
    private const ONE   = '   '.PHP_EOL.'  |'.PHP_EOL.'  |';
    private const TWO   = ' _ '.PHP_EOL.' _|'.PHP_EOL.'|_ ';
    private const THREE = ' _ '.PHP_EOL.' _|'.PHP_EOL.' _|';
    private const FOUR  = '   '.PHP_EOL.'|_|'.PHP_EOL.'  |';
    private const FIVE  = ' _ '.PHP_EOL.'|_ '.PHP_EOL.' _|';
    private const SIX   = ' _ '.PHP_EOL.'|_ '.PHP_EOL.'|_|';
    private const SEVEN = ' _ '.PHP_EOL.'  |'.PHP_EOL.'  |';
    private const EIGHT = ' _ '.PHP_EOL.'|_|'.PHP_EOL.'|_|';
    private const NINE  = ' _ '.PHP_EOL.'|_|'.PHP_EOL.' _|';
    private const NONE  = '   '.PHP_EOL.'|  '.PHP_EOL.'  |';

    private const SIGNATURES = [
        self::ZERO,
        self::ONE,
        self::TWO,
        self::THREE,
        self::FOUR,
        self::FIVE,
        self::SIX,
        self::SEVEN,
        self::EIGHT,
        self::NINE
    ];

    /** @var DigitsSignaturesDictionary */
    private $sut;

    protected function setUp(): void
    {
        $this->sut = new DigitsSignaturesDictionary();
    }

    /**
     * @test
     */
    public function testItShouldMatchDigits()
    {
        foreach (self::SIGNATURES as $key => $signature) {
            $result = $this->sut->match($signature);
            $this->assertSame((string) $key, $result);
        }
    }

    /**
     * @test
     */
    public function testItShouldReturnUnrecognizedSignatureIfNotFoundMatch()
    {
        $result = $this->sut->match(self::NONE);

        $this->assertSame('?', $result);
    }

    /**
     * @test
     * @param $sourceSignature
     * @param $expected
     *
     * @dataProvider similarDigitsProvider
     */
    public function testItShouldReturnSimilarMatch(string $sourceSignature, array $expected)
    {
        $result = $this->sut->findSimilar($sourceSignature, 1);
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function similarDigitsProvider(): array
    {
        return [
            [self::ONE,   ['7']],
            [self::TWO,   []],
            [self::THREE, ['9']],
            [self::FOUR,  []],
            [self::FIVE,  ['6', '9']],
            [self::SIX,   ['5', '8']],
            [self::SEVEN, ['1']],
            [self::EIGHT, ['0', '6', '9']],
            [self::NINE,  ['3', '5', '8']],
            [self::NONE,  []],
        ];
    }
}
