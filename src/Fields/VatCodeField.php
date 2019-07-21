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

    /**
     * @return $this
     */
    public function setVatCode(?VatCode $vatCode): self
    {
        $this->vatCode = $vatCode;
        return $this;
    }
}
