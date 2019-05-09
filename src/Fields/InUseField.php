<?php

namespace PhpTwinfield\Fields;

trait InUseField
{
    /**
     * In use field
     * Used by: Activity, ArticleLine, AssetMethod, CostCenter, Customer, FixedAsset, GeneralLedger, Project, Supplier
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

    public function getInUseToString(): ?string
    {
        return ($this->getInUse()) ? 'true' : 'false';
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

    /**
     * @param string|null $inUseString
     * @return $this
     * @throws Exception
     */
    public function setInUseFromString(?string $inUseString)
    {
        return $this->setInUse(filter_var($inUseString, FILTER_VALIDATE_BOOLEAN));
    }
}