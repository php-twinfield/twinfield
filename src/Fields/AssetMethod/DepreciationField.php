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

    public function getDepreciationToCode(): ?string
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
     * @param string|null $depreciationCode
     * @return $this
     * @throws Exception
     */
    public function setDepreciationFromCode(?string $depreciationCode)
    {
        $depreciation = new GeneralLedger();
        $depreciation->setCode($depreciationCode);
        return $this->setDepreciation($depreciation);
    }
}