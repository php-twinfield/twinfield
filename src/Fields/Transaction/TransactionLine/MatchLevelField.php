<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait MatchLevelField
{
    /**
     * Match level field
     * Used by: BaseTransactionLine
     *
     * @var int|null
     */
    private $matchLevel;

    /**
     * @return null|int
     */
    public function getMatchLevel(): ?int
    {
        return $this->matchLevel;
    }

    /**
     * @param null|int $matchLevel
     * @return $this
     */
    public function setMatchLevel(?int $matchLevel): self
    {
        $this->matchLevel = $matchLevel;
        return $this;
    }
}