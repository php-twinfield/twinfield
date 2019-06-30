<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use Money\Money;

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
     * @param Money|null $stopValue
     * @return $this
     */
    public function setStopValue(?Money $stopValue)
    {
        $this->stopValue = $stopValue;

        return $this;
    }
}
