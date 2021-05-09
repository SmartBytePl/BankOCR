<?php

declare(strict_types=1);

namespace BankOCR\Dictionary;

interface DictionaryInterface
{
    /**
     * @param string $sourceSignature
     *
     * @return string
     */
    public function match(string $sourceSignature): string;

    /**
     * @param string $sourceSignature
     * @param int $distance
     *
     * @return array|string[]
     */
    public function findSimilar(string $sourceSignature, int $distance = 1): array;
}
