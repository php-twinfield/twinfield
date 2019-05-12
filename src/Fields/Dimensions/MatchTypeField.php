<?php

namespace PhpTwinfield\Fields\Dimensions;

use PhpTwinfield\Enums\MatchType;

trait MatchTypeField
{
    /**
     * Match type field
     * Used by: CustomerFinancials, FixedAssetFinancials, GeneralLedgerFinancials, SupplierFinancials
     *
     * @var MatchType|null
     */
    private $matchType;

    public function getMatchType(): ?MatchType
    {
        return $this->matchType;
    }

    /**
     * @param MatchType|null $matchType
     * @return $this
     */
    public function setMatchType(?MatchType $matchType): self
    {
        $this->matchType = $matchType;
        return $this;
    }

    /**
     * @param string|null $matchTypeString
     * @return $this
     * @throws Exception
     */
    public function setMatchTypeFromString(?string $matchTypeString)
    {
        return $this->setMatchType(new MatchType((string)$matchTypeString));
    }
}