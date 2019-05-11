<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodProfitLossAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait SalesField
{
    /**
     * @var GeneralLedger|null
     */
    private $sales;

    public function getSales(): ?GeneralLedger
    {
        return $this->sales;
    }

    public function getSalesToString(): ?string
    {
        if ($this->getSales() != null) {
            return $this->sales->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setSales(?GeneralLedger $sales): self
    {
        $this->sales = $sales;
        return $this;
    }

    /**
     * @param string|null $salesCode
     * @return $this
     * @throws Exception
     */
    public function setSalesFromString(?string $salesCode)
    {
        $sales = new GeneralLedger();
        $sales->setCode($salesCode);
        return $this->setSales($sales);
    }
}