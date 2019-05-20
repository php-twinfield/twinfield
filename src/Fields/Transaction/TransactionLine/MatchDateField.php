<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Match date field
 * Used by: MatchSet, PurchaseTransactionLine
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
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
     * @return string|null
     */
    public function getMatchDateToString(): ?string
    {
        if ($this->getMatchDate() != null) {
            return Util::formatDateTime($this->getMatchDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $matchDateString
     * @return $this
     * @throws Exception
     */
    public function setMatchDateFromString(?string $matchDateString)
    {
        if ((bool)strtotime($matchDateString)) {
            return $this->setMatchDate(Util::parseDateTime($matchDateString));
        } else {
            return $this->setMatchDate(null);
        }
    }
}