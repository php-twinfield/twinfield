<?php

namespace PhpTwinfield\Fields;

trait IDField
{
    /**
     * ID field
     * Used by: ArticleLine, AssetMethodFreeText, CustomerAddress, CustomerBank, CustomerPostingRule, InvoiceLine, RateRateChange, SupplierAddress, SupplierBank, SupplierPostingRule, VatCodeAccount
     *
     * @var int|null
     */
    private $ID;

    /**
     * @return null|int
     */
    public function getID(): ?int
    {
        return $this->ID;
    }

    /**
     * @param null|int $ID
     * @return $this
     */
    public function setID(?int $ID): self
    {
        $this->ID = $ID;
        return $this;
    }
}