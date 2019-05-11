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

    public function getDim1ToString(): ?string
    {
        if ($this->getDim1() != null) {
            return $this->dim1->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDim1(?GeneralLedger $dim1): self
    {
        $this->dim1 = $dim1;
        return $this;
    }

    /**
     * @param string|null $dim1Code
     * @return $this
     * @throws Exception
     */
    public function setDim1FromString(?string $dim1Code)
    {
        $dim1 = new GeneralLedger();
        $dim1->setCode($dim1Code);
        return $this->setDim1($dim1);
    }
}