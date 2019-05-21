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

    public function getDepreciationToString(): ?string
    {
        if ($this->getDepreciation() != null) {
            return $this->depreciation->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setDepreciation(?GeneralLedger $depreciation): self
    {
        $this->depreciation = $depreciation;
        return $this;
    }

    /**
     * @param string|null $depreciationString
     * @return $this
     * @throws Exception
     */
    public function setDepreciationFromString(?string $depreciationString)
    {
        $depreciation = new GeneralLedger();
        $depreciation->setCode($depreciationString);
        return $this->setDepreciation($depreciation);
    }
}