<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use Money\Money;
use PhpTwinfield\Util;

trait BaseCreditLimitField
{
    /**
     * Base credit limit field
     * Used by: CustomerCreditManagement
     *
     * @var Money|null
     */
    private $baseCreditLimit;

    /**
     * @return Money|null
     */
    public function getBaseCreditLimit(): ?Money
    {
        return $this->baseCreditLimit;
    }

    /**
     * @return float|null
     */
    public function getBaseCreditLimitToFloat(): ?float
    {
        if ($this->getBaseCreditLimit() != null) {
            return Util::formatMoney($this->getBaseCreditLimit());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $baseCreditLimit
     * @return $this
     */
    public function setBaseCreditLimit(?Money $baseCreditLimit)
    {
        $this->baseCreditLimit = $baseCreditLimit;

        return $this;
    }

    /**
     * @param float|null $baseCreditLimitFloat
     * @return $this
     * @throws Exception
     */
    public function setBaseCreditLimitFromFloat(?float $baseCreditLimitFloat)
    {
        if ((float)$baseCreditLimitFloat) {
            return $this->setBaseCreditLimit(Money::EUR(100 * $baseCreditLimitFloat));
        } else {
            return $this->setBaseCreditLimit(Money::EUR(0));
        }
    }
}