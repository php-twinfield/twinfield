<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

/**
 * Sell date field
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 */
trait SellDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $sellDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getSellDate(): ?\DateTimeInterface
    {
        return $this->sellDate;
    }

    /**
     * @param \DateTimeInterface|null $sellDate
     * @return $this
     */
    public function setSellDate(?\DateTimeInterface $sellDate)
    {
        $this->sellDate = $sellDate;
        return $this;
    }
}
