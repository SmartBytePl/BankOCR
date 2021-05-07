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
     * @param InputValidatorInterface[] $inputValidators
     * @param OutputValidatorInterface[] $outputValidators
     * @param Parser $parser
     */
    public function __construct(array $inputValidators, array $outputValidators, Parser $parser)
    {
        $this->inputValidators = $inputValidators;
        $this->outputValidators = $outputValidators;
        $this->parser = $parser;
    }

    /**
     * @param string $input
     *
     * @return array<int, string>
     */
    public function recognize(string $input): array
    {
        $results = [];
        foreach ($this->splitInput($input) as $entryIndex => $entry) {
            try {
                $this->validateInput($entry);
                $recognized = $this->parser->parse($entry);
                $this->validateOutput($recognized);
                $results[] = $recognized;
            } catch (InputValidationException $e) {
                $results[] = 'Skipping entry index: '.$entryIndex.' is not valid. '.$e->getMessage();
            } catch (OutputValidationException $e) {
                $results[] = $recognized . ' ' . $e->getSymbol();
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
    private function splitInput(string $input): array
    {
        $inputArray = explode(PHP_EOL, $input);
        $chunks = array_chunk($inputArray, self::INPUT_ENTRY_LINES_COUNT);

        $results = [];
        foreach ($chunks as $chunk) {
            $results[] = implode(PHP_EOL, $chunk);
        }

        return $results;
    }

}
