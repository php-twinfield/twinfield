<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait ToBeInvoicedField
{
    /**
     * @var GeneralLedger|null
     */
    private $toBeInvoiced;

    public function getToBeInvoiced(): ?GeneralLedger
    {
        return $this->toBeInvoiced;
    }

    public function getToBeInvoicedToCode(): ?string
    {
        if ($this->getToBeInvoiced() != null) {
            return $this->toBeInvoiced->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setToBeInvoiced(?GeneralLedger $toBeInvoiced): self
    {
        $this->toBeInvoiced = $toBeInvoiced;
        return $this;
    }

    /**
     * @param string|null $toBeInvoicedCode
     * @return $this
     * @throws Exception
     */
    public function setToBeInvoicedFromCode(?string $toBeInvoicedCode)
    {
        $toBeInvoiced = new GeneralLedger();
        $toBeInvoiced->setCode($toBeInvoicedCode);
        return $this->setToBeInvoiced($toBeInvoiced);
    }
}