# BankOCR

Solution of task:
https://codingdojo.org/kata/BankOCR/

Requirements:
- git
- php >= 7.3
- composer

In order to run:
```
git clone git@github.com:SmartBytePl/BankOCR.git
cd BankOCR
composer install
```

How to use example:
```
php index.php < tests/fixtures/use_case_4_in.txt
```

To run unit tests:
```
./vendor/bin/phpunit --colors --bootstrap vendor/autoload.php tests
```
To run functional tests:
```
./vendor/bin/phpunit --testdox --colors --bootstrap vendor/autoload.php tests/FunctionalTests.php
```

Credits:
- tests cases used from https://github.com/dvrensk/bank_ocr_kata
