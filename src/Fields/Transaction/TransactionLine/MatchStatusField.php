<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use PhpTwinfield\Enums\MatchStatus;

trait MatchStatusField
{
    /**
     * Match status field
     *
     * @var MatchStatus|null
     */
    private $matchStatus;

    public function getStatus(): ?MatchStatus
    {
        return $this->matchStatus;
    }

    /**
     * @param MatchStatus|null $matchStatus
     * @return $this
     */
    public function setMatchStatus(?MatchStatus $matchStatus): self
    {
        $this->matchStatus = $matchStatus;
        return $this;
    }

    /**
     * @param string|null $matchStatusString
     * @return $this
     * @throws Exception
     */
    public function setMatchStatusFromString(?string $matchStatusString)
    {
        return $this->setMatchStatus(new MatchStatus((string)$matchStatusString));
    }
}