<?php

namespace PhpTwinfield\Fields\Level1234\Level34\FixedAsset;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Sell date field
 * Used by: FixedAssetFixedAssets
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
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
     * @return string|null
     */
    public function getSellDateToString(): ?string
    {
        if ($this->getSellDate() != null) {
            return Util::formatDate($this->getSellDate());
        } else {
            return null;
        }
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

    /**
     * @param string|null $sellDateString
     * @return $this
     * @throws Exception
     */
    public function setSellDateFromString(?string $sellDateString)
    {
        if ((bool)strtotime($sellDateString)) {
            return $this->setSellDate(Util::parseDate($sellDateString));
        } else {
            return $this->setSellDate(null);
        }
    }
}