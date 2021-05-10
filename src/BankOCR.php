<?php

declare(strict_types=1);

namespace BankOCR;

use BankOCR\Exceptions\Input\InputValidationException;
use BankOCR\Exceptions\Output\OutputValidationException;
use BankOCR\Validators\Input\InputValidatorInterface;
use BankOCR\Validators\Output\OutputValidatorInterface;

class BankOCR
{
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
     * @param string $bulkInput
     * @param bool $findSimilar
     *
     * @return array<int, string>
     */
    public function recognize(string $bulkInput, bool $findSimilar = true): array
    {
        $results = [];
        foreach ($this->parser->splitBulkInput($bulkInput) as $inputIndex => $input) {
            try {
                $this->validateInput($input);
                $recognized = $this->parser->parse($input);
                $this->validateOutput($recognized);
                $results[] = $recognized;
            } catch (InputValidationException $e) {
                $results[] = 'Skipping entry index: '.$inputIndex.' is not valid. '.$e->getMessage();
            } catch (OutputValidationException $e) {
                $errorMessage = ' '.$e->getSymbol();
                if ($findSimilar) {
                    $alternatives = $this->parser->parseSimilar($input);
                    $validAlternatives = $this->filterValidAlternatives($alternatives);
                    $recognized = $this->resolveBestMatch($validAlternatives, $recognized);
                    $errorMessage = $this->resolveErrorMessage($validAlternatives);
                }

                $results[] = $recognized. $errorMessage;
            }
        }

        return $results;
    }

    /**
     * @param array|string[] $alternatives
     * @param string $recognized
     *
     * @return string
     */
    private function resolveBestMatch(array $alternatives, string $recognized): string
    {
        return count($alternatives) === 1 ? $alternatives[0] : $recognized;
    }

    /**
     * @param $alternatives
     *
     * @return string
     */
    private function resolveErrorMessage($alternatives): string
    {
        return count($alternatives) <= 1 ? '' : " AMB ['".implode("', '",$alternatives)."']";
    }

    /**
     * @param array|string[] $alternatives
     * @return array|string[]
     */
    private function filterValidAlternatives(array $alternatives): array
    {
        $results = [];
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
     * @param
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
}
