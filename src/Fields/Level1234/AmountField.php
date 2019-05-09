<?php

namespace PhpTwinfield\Fields\Level1234;

use Money\Money;
use PhpTwinfield\Util;

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
     * @return float|null
     */
    public function getAmountToFloat(): ?float
    {
        if ($this->getAmount() != null) {
            return Util::formatMoney($this->getAmount());
        } else {
            return 0;
        }
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

    /**
     * @param float|null $amountFloat
     * @return $this
     * @throws Exception
     */
    public function setAmountFromFloat(?float $amountFloat)
    {
        if ((float)$amountFloat) {
            return $this->setAmount(Money::EUR(100 * $amountFloat));
        } else {
            return $this->setAmount(Money::EUR(0));
        }
    }
}