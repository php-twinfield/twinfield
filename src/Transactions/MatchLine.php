<?php

namespace PhpTwinfield\Transactions;

use Money\Money;

class MatchLine
{
    /** @var string */
    private $code;

    /** @var int */
    private $number;

    /** @var int */
    private $line;

    /** @var Money */
    private $matchValue;

    public function __construct(
        string $code,
        int $number,
        int $line,
        Money $matchValue
    )
    {
        $this->code = $code;
        $this->number = $number;
        $this->line = $line;
        $this->matchValue = $matchValue;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getMatchValue(): Money
    {
        return $this->matchValue;
    }
}