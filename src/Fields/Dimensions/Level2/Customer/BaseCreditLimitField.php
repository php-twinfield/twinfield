<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

use Money\Money;

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
     * @param Money|null $baseCreditLimit
     * @return $this
     */
    public function setBaseCreditLimit(?Money $baseCreditLimit)
    {
        $this->baseCreditLimit = $baseCreditLimit;

        return $this;
    }
}
