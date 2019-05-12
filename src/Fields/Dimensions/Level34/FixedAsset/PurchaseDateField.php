<?php

namespace PhpTwinfield\Fields\Dimensions\Level34\FixedAsset;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Purchase date field
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
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
     * @return string|null
     */
    public function getPurchaseDateToString(): ?string
    {
        if ($this->getPurchaseDate() != null) {
            return Util::formatDate($this->getPurchaseDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $purchaseDateString
     * @return $this
     * @throws Exception
     */
    public function setPurchaseDateFromString(?string $purchaseDateString)
    {
        if ((bool)strtotime($purchaseDateString)) {
            return $this->setPurchaseDate(Util::parseDate($purchaseDateString));
        } else {
            return $this->setPurchaseDate(null);
        }
    }
}