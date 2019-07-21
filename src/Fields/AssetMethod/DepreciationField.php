<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts, AssetMethodProfitLossAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait DepreciationField
{
    /**
     * @var GeneralLedger|null
     */
    private $depreciation;

    public function getDepreciation(): ?GeneralLedger
    {
        return $this->depreciation;
    }

    /**
     * @return $this
     */
    public function setDepreciation(?GeneralLedger $depreciation): self
    {
        $this->depreciation = $depreciation;
        return $this;
    }
}
