<?php


namespace BankOCR;

use BankOCR\Dictionary\DictionaryInterface;

class AlternativesGuesser
{
    private const LEVENSHTEIN_DISTANCE = 1;

    /** @var DictionaryInterface */
    private $dictionary;

    /**
     * @param DictionaryInterface $dictionary
     */
    public function __construct(DictionaryInterface $dictionary)
    {
        $this->dictionary = $dictionary;
    }


    private function generateSimilarInputs(string $input): array
    {
        $similarInputArray = $this->generateSimilarInputArray($input);

        foreach($similarInputArray as $positions => $similarDigits) {


        }

    }

    private function generateSimilarInputArray(string $input): array
    {
        $alternatives = [];
        foreach (str_split($input) as $position => $digit) {
            if (!isset($alternatives[$position])) {
                $alternatives[$position] = [];
            }
            $alternatives[$position] = $this->dictionary->findSimilar($digit, self::LEVENSHTEIN_DISTANCE);
        }

        return $alternatives;
    }

}
