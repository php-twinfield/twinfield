<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

/**
 * Purchase date field
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 */
trait PurchaseDateField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $purchaseDate;

    /**
     * @return \DateTimeInterface|null
     */
    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    /**
     * @param \DateTimeInterface|null $purchaseDate
     * @return $this
     */
    public function setPurchaseDate(?\DateTimeInterface $purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }
}
