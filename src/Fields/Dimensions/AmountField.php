<?php

namespace PhpTwinfield\Fields\Dimensions;

use Money\Money;

trait AmountField
{
    /**
     * Amount field
     * Used by: CustomerPostingRule, FixedAssetTransactionLine, SupplierPostingRule
     *
     * @var Money|null
     */
    private $amount;

    /**
     * @return Money|null
     */
    public function getAmount(): ?Money
    {
        return $this->amount;
    }

    /**
     * @param Money|null $amount
     * @return $this
     */
    public function setAmount(?Money $amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
