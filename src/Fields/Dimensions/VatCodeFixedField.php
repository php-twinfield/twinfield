<?php

namespace PhpTwinfield\Fields\Dimensions;

trait VatCodeFixedField
{
    /**
     * VAT code locked field
     * Used by: CustomerFinancials, FixedAssetFinancials, GeneralLedgerFinancials, SupplierFinancials
     *
     * @var bool
     */
    private $vatCodeFixed;

    /**
     * @return bool
     */
    public function getVatCodeFixed(): ?bool
    {
        return $this->vatCodeFixed;
    }

    /**
     * @param bool $vatCodeFixed
     * @return $this
     */
    public function setVatCodeFixed(?bool $vatCodeFixed): self
    {
        $this->vatCodeFixed = $vatCodeFixed;
        return $this;
    }
}
