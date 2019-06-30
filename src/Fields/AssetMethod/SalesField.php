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

    /**
     * @return $this
     */
    public function setSales(?GeneralLedger $sales): self
    {
        $this->sales = $sales;
        return $this;
    }
}
