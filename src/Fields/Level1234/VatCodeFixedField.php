<?php

namespace PhpTwinfield\Fields\Level1234;

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

    public function getVatCodeFixedToString(): ?string
    {
        return ($this->getVatCodeFixed()) ? 'true' : 'false';
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

    /**
     * @param string|null $vatCodeFixedString
     * @return $this
     * @throws Exception
     */
    public function setVatCodeFixedFromString(?string $vatCodeFixedString)
    {
        return $this->setVatCodeFixed(filter_var($vatCodeFixedString, FILTER_VALIDATE_BOOLEAN));
    }
}