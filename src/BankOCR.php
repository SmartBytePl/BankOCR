<?php

declare(strict_types=1);

namespace BankOCR;

use BankOCR\Exceptions\Input\InputValidationException;
use BankOCR\Exceptions\Output\OutputValidationException;
use BankOCR\Validators\Input\InputValidatorInterface;
use BankOCR\Validators\Output\OutputValidatorInterface;

class BankOCR
{
    private const INPUT_ENTRY_LINES_COUNT = 4;

    /** @var InputValidatorInterface[] */
    private $inputValidators;

    /** @var OutputValidatorInterface[] */
    private $outputValidators;

    /** @var Parser */
    private $parser;

    /**
     * @param Parser $parser
     * @param InputValidatorInterface[] $inputValidators
     * @param OutputValidatorInterface[] $outputValidators
     */
    public function __construct(Parser $parser, array $inputValidators, array $outputValidators)
    {
        $this->inputValidators = $inputValidators;
        $this->outputValidators = $outputValidators;
        $this->parser = $parser;
    }

    /**
     * @param string $input
     * @param bool $findSimilar
     *
     * @return array<int, string>
     */
    public function recognize(string $input, bool $findSimilar = true): array
    {
        $results = [];
        foreach ($this->splitBulkInput($input) as $entryIndex => $entry) {
            try {
                $this->validateInput($entry);
                $recognized = $this->parser->parse($entry);
                $this->validateOutput($recognized);
                $results[] = $recognized;
            } catch (InputValidationException $e) {
                $results[] = 'Skipping entry index: '.$entryIndex.' is not valid. '.$e->getMessage();
            } catch (OutputValidationException $e) {
                $errorMessage = ' '.$e->getSymbol();
                if ($findSimilar) {
                    $alternatives = $this->getValidAlternatives($entry);
                    $recognized = $this->resolveBestMatch($alternatives, $recognized);
                    $errorMessage = $this->resolveErrorMessage($alternatives);
                }

                $results[] = $recognized. $errorMessage;
            }
        }

        return $results;
    }

    private function resolveBestMatch(array $alternatives, string $recognized)
    {
        return count($alternatives) === 1 ? $alternatives[0] : $recognized;
    }

    private function resolveErrorMessage($alternatives)
    {
        return count($alternatives) <= 1 ? '' : " AMB ['".implode("', '",$alternatives)."']";
    }

    private function getValidAlternatives(string $entry): array
    {
        $results = [];
        $alternatives = $this->parser->parseSimilar($entry);
        foreach ($alternatives as $alternative) {
            try {
                $this->validateOutput($alternative);
                $results[] = $alternative;
            } catch (OutputValidationException $e) {
            }
        }

        return $results;
    }

    /**
     * @param string $entry
     * @throws InputValidationException
     */
    private function validateInput(string $entry): void
    {
        foreach ($this->inputValidators as $validator) {
            $validator->assertIsValid($entry);
        }
    }

    /**
     * @param string $entry
     * @throws OutputValidationException
     */
    private function validateOutput(string $entry): void
    {
        foreach ($this->outputValidators as $validator) {
            $validator->assertIsValid($entry);
        }
    }

    /**
     * @param string $input
     * @return array
     */
    private function splitBulkInput(string $input): array
    {
        $inputArray = explode(PHP_EOL, $input);
        $chunks = array_chunk($inputArray, self::INPUT_ENTRY_LINES_COUNT);

        $results = [];
        foreach ($chunks as $chunk) {
            if (count($chunk) < self::INPUT_ENTRY_LINES_COUNT) {
                continue;
            }
            $results[] = implode(PHP_EOL, $chunk);
        }

        return $results;
    }

}
