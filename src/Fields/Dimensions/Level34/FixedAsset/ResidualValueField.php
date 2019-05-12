<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use Money\Money;
use PhpTwinfield\Util;

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
     * @return float|null
     */
    public function getResidualValueToFloat(): ?float
    {
        if ($this->getResidualValue() != null) {
            return Util::formatMoney($this->getResidualValue());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $residualValueFloat
     * @return $this
     * @throws Exception
     */
    public function setResidualValueFromFloat(?float $residualValueFloat)
    {
        if ((float)$residualValueFloat) {
            return $this->setResidualValue(Money::EUR(100 * $residualValueFloat));
        } else {
            return $this->setResidualValue(Money::EUR(0));
        }
    }
}