<?php

namespace PhpTwinfield\Fields\AssetMethod;

use PhpTwinfield\GeneralLedger;

/**
 * The dimension
 * Used by: AssetMethodBalanceAccounts
 *
 * @package PhpTwinfield\Traits
 */
trait PurchaseValueField
{
    /**
     * @var GeneralLedger|null
     */
    private $purchaseValue;

    public function getPurchaseValue(): ?GeneralLedger
    {
        return $this->purchaseValue;
    }

    public function getPurchaseValueToString(): ?string
    {
        if ($this->getPurchaseValue() != null) {
            return $this->purchaseValue->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setPurchaseValue(?GeneralLedger $purchaseValue): self
    {
        $this->purchaseValue = $purchaseValue;
        return $this;
    }

    /**
     * @param string|null $purchaseValueString
     * @return $this
     * @throws Exception
     */
    public function setPurchaseValueFromString(?string $purchaseValueString)
    {
        $purchaseValue = new GeneralLedger();
        $purchaseValue->setCode($purchaseValueString);
        return $this->setPurchaseValue($purchaseValue);
    }
}