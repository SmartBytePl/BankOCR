<?php

declare(strict_types=1);

namespace BankOCR\Dictionary;

class DigitsSignaturesDictionary extends DictionaryAbstract
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

    private const UNRECOGNIZED_SIGNATURE = '?';

    /**
     * {@inheritDoc}
     */
    protected function signatures(): array
    {
        return [
            self::ZERO,
            self::ONE,
            self::TWO,
            self::THREE,
            self::FOUR,
            self::FIVE,
            self::SIX,
            self::SEVEN,
            self::EIGHT,
            self::NINE,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function unrecognizedSignature(): string
    {
        return self::UNRECOGNIZED_SIGNATURE;
    }
}

