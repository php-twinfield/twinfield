<?php

namespace PhpTwinfield\Fields;

trait InUseField
{
    /**
     * In use field
     * Used by: Activity, ArticleLine, AssetMethod, CostCenter, Customer, FixedAsset, GeneralLedger, Project, Supplier, VatCodePercentage
     *
     * @var bool
     */
    private $inUse;

    /**
     * @return bool
     */
    public function getInUse(): ?bool
    {
        return $this->inUse;
    }

    /**
     * @param bool $inUse
     * @return $this
     */
    public function setInUse(?bool $inUse): self
    {
        $this->inUse = $inUse;
        return $this;
    }
}
