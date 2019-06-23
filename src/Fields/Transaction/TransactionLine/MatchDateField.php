<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

/**
 * Match date field
 * Used by: MatchSet, PurchaseTransactionLine
 *
 * @package PhpTwinfield\Traits
 */
trait MatchDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $matchDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getMatchDate(): ?\DateTimeInterface
    {
        return $this->matchDate;
    }

    /**
     * @param \DateTimeInterface|null $matchDate
     * @return $this
     */
    public function setMatchDate(?\DateTimeInterface $matchDate)
    {
        $this->matchDate = $matchDate;
        return $this;
    }
}