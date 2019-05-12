<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use Money\Money;
use PhpTwinfield\Util;

trait StopValueField
{
    /**
     * Stop value field
     * Used by: FixedAssetFixedAssets
     *
     * @var Money|null
     */
    private $stopValue;

    /**
     * @return Money|null
     */
    public function getStopValue(): ?Money
    {
        return $this->stopValue;
    }

    /**
     * @return float|null
     */
    public function getStopValueToFloat(): ?float
    {
        if ($this->getStopValue() != null) {
            return Util::formatMoney($this->getStopValue());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $stopValue
     * @return $this
     */
    public function setStopValue(?Money $stopValue)
    {
        $this->stopValue = $stopValue;

        return $this;
    }

    /**
     * @param float|null $stopValueFloat
     * @return $this
     * @throws Exception
     */
    public function setStopValueFromFloat(?float $stopValueFloat)
    {
        if ((float)$stopValueFloat) {
            return $this->setStopValue(Money::EUR(100 * $stopValueFloat));
        } else {
            return $this->setStopValue(Money::EUR(0));
        }
    }
}