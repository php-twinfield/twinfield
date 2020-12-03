<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use DateTimeInterface;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Exception;

trait MatchDateField
{
    /**
     * @var DateTimeInterface|null Only if line type is total. Read-only attribute.
     *                             The date on which the invoice is matched.
     */
    private $matchDate;

    abstract public function getLineType(): LineType;

    /**
     * @return DateTimeInterface|null
     */
    public function getMatchDate(): ?DateTimeInterface
    {
        return $this->matchDate;
    }

    /**
     * @param DateTimeInterface|null $matchDate
     * @return $this
     * @throws Exception
     */
    public function setMatchDate(?DateTimeInterface $matchDate): self
    {
        if ($matchDate !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('matchdate', $this);
        }

        $this->matchDate = $matchDate;

        return $this;
    }
}
