<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: FixedAssetTransactionLine, InvoiceLine, VatCodeAccount
 *
 * @package PhpTwinfield\Traits
 */
trait Dim1Field
{
    /**
     * @var GeneralLedger|null
     */
    private $dim1;

    public function getDim1(): ?GeneralLedger
    {
        return $this->dim1;
    }

    /**
     * @return $this
     */
    public function setDim1(?GeneralLedger $dim1): self
    {
        $this->dim1 = $dim1;
        return $this;
    }
}