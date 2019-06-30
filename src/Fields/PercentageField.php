<?php

namespace PhpTwinfield\Fields;

trait PercentageField
{
    /**
     * Percentage field
     * Used by: AssetMethod, FixedAssetFixedAssets, VatCodeAccount, VatCodePercentage
     *
     * @var float|null
     */
    private $percentage;

    /**
     * @return null|float
     */
    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    /**
     * @param null|float $percentage
     * @return $this
     */
    public function setPercentage(?float $percentage): self
    {
        $this->percentage = $percentage;
        return $this;
    }
}
