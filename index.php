<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use \BankOCR\Factory\BankOCRFactory;

$bankOCRFactory = new BankOCRFactory();
$bankOCR = $bankOCRFactory->createBankAccountOCR();

$bulkInput = file_get_contents('php://stdin');
$results = $bankOCR->recognize($bulkInput);

echo implode(PHP_EOL, $results).PHP_EOL;
