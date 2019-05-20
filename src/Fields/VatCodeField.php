<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\VatCode;

/**
 * The VAT code
 * Used by: Activity, Article, BaseTransactionLine, CustomerFinancials, CustomerLine, FixedAssetFinancials, GeneralLedgerFinancials, InvoiceLine, InvoiceVatLine, Project, SupplierFinancials, SupplierLine
 *
 * @package PhpTwinfield\Traits
 */
trait VatCodeField
{
    /**
     * @var VatCode|null
     */
    private $vatCode;

    public function getVatCode(): ?VatCode
    {
        return $this->vatCode;
    }

    public function getVatCodeToString(): ?string
    {
        if ($this->getVatCode() != null) {
            return $this->vatCode->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setVatCode(?VatCode $vatCode): self
    {
        $this->vatCode = $vatCode;
        return $this;
    }

    /**
     * @param string|null $vatCodeCode
     * @return $this
     * @throws Exception
     */
    public function setVatCodeFromString(?string $vatCodeCode)
    {
        $vatCode = new VatCode();
        $vatCode->setCode($vatCodeCode);
        return $this->setVatCode($vatCode);
    }
}
