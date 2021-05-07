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
}
