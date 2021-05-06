<?php

declare(strict_types=1);

namespace BankOCR\Enum;

use BankOCR\EntryParser;

interface DigitsSignaturesEnumInterface
{
    public const ZERO  = ' _ '.EntryParser::LINE_DELIMITER.'| |'.EntryParser::LINE_DELIMITER.'|_|';
    public const ONE   = '   '.EntryParser::LINE_DELIMITER.'  |'.EntryParser::LINE_DELIMITER.'  |';
    public const TWO   = ' _ '.EntryParser::LINE_DELIMITER.' _|'.EntryParser::LINE_DELIMITER.'|_ ';
    public const THREE = ' _ '.EntryParser::LINE_DELIMITER.' _|'.EntryParser::LINE_DELIMITER.' _|';
    public const FOUR  = '   '.EntryParser::LINE_DELIMITER.'|_|'.EntryParser::LINE_DELIMITER.'  |';
    public const FIVE  = ' _ '.EntryParser::LINE_DELIMITER.'|_ '.EntryParser::LINE_DELIMITER.' _|';
    public const SIX   = ' _ '.EntryParser::LINE_DELIMITER.'|_ '.EntryParser::LINE_DELIMITER.'|_|';
    public const SEVEN = ' _ '.EntryParser::LINE_DELIMITER.'  |'.EntryParser::LINE_DELIMITER.'  |';
    public const EIGHT = ' _ '.EntryParser::LINE_DELIMITER.'|_|'.EntryParser::LINE_DELIMITER.'|_|';
    public const NINE  = ' _ '.EntryParser::LINE_DELIMITER.'|_|'.EntryParser::LINE_DELIMITER.' _|';

    public const SIGNATURES = [
      self::ZERO,
      self::ONE,
      self::TWO,
      self::THREE,
      self::FOUR,
      self::FIVE,
      self::SIX,
      self::SEVEN,
      self::EIGHT,
      self::NINE,
    ];
}
