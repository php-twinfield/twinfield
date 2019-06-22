<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use Money\Money;

trait ResidualValueField
{
    /**
     * Residual value field
     * Used by: FixedAssetFixedAssets
     *
     * @var Money|null
     */
    private $residualValue;

    /**
     * @return Money|null
     */
    public function getResidualValue(): ?Money
    {
        return $this->residualValue;
    }

    /**
     * @param Money|null $residualValue
     * @return $this
     */
    public function setResidualValue(?Money $residualValue)
    {
        $this->residualValue = $residualValue;

        return $this;
    }
}