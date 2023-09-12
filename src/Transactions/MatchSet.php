<?php

namespace PhpTwinfield\Transactions;

use DateTimeInterface;
use Money\Money;
use PhpTwinfield\Enums\Destiny;

class MatchSet
{
    /** @var Destiny */
    private $status;

    /** @var DateTimeInterface */
    private $matchDate;

    /** @var Money */
    private $matchValue;

    /** @var MatchLine[] */
    private $matchLines;

    /**
     * @param Destiny $status
     * @param DateTimeInterface $matchDate
     * @param Money $matchValue
     * @param MatchLine[] $matchLines
     */
    public function __construct(
        Destiny $status,
        DateTimeInterface $matchDate,
        Money $matchValue,
        array $matchLines
    )
    {
        $this->status = $status;
        $this->matchDate = $matchDate;
        $this->matchValue = $matchValue;
        $this->matchLines = $matchLines;
    }

    public function getStatus(): Destiny
    {
        return $this->status;
    }

    public function getMatchDate(): DateTimeInterface
    {
        return $this->matchDate;
    }

    public function getMatchValue(): Money
    {
        return $this->matchValue;
    }

    /**
     * @return MatchLine[]
     */
    public function getMatchLines(): array
    {
        return $this->matchLines;
    }
}