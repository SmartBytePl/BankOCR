<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use \BankOCR\Factory\BankOCRFactory;

$bankOCRFactory = new BankOCRFactory();
$bankOCR = $bankOCRFactory->createBankAccountOCR();

$input = '';
$bankOCR->recognize($input);
