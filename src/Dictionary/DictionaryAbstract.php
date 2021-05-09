<?php


namespace BankOCR\Dictionary;


class DictionaryAbstract implements DictionaryInterface
{
    /**
     * @return array|string[]
     */
    protected function signatures(): array{}

    /**
     * @return string
     */
    protected function unrecognizedSignature(): string{}

    /**
     * {@inheritDoc}
     */
    public function match(string $sourceSignature): string
    {
        foreach ($this->signatures() as $key => $signature) {
            if ($sourceSignature === $signature) {
                return (string) $key;
            }
        }

        return $this->unrecognizedSignature();
    }

    /**
     * {@inheritDoc}
     */
    public function findSimilar(string $sourceSignature, int $distance = 1): array
    {
        $similar = [];
        foreach ($this->signatures() as $key => $signature) {
            if (levenshtein($sourceSignature, $signature) === $distance) {
                $similar[] = (string) $key;
            }
        }

        return $similar;
    }
}
