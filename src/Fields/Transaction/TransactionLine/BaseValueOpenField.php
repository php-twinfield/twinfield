<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait BaseValueOpenField
{
    /**
     * Base value open field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $baseValueOpen;

    /**
     * @return Money|null
     */
    public function getBaseValueOpen(): ?Money
    {
        return $this->baseValueOpen;
    }

    /**
     * @return float|null
     */
    public function getBaseValueOpenToFloat(): ?float
    {
        if ($this->getBaseValueOpen() != null) {
            return Util::formatMoney($this->getBaseValueOpen());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $baseValueOpen
     * @return $this
     */
    public function setBaseValueOpen(?Money $baseValueOpen)
    {
        $this->baseValueOpen = $baseValueOpen;

        return $this;
    }

    /**
     * @param float|null $baseValueOpenFloat
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpenFromFloat(?float $baseValueOpenFloat)
    {
        if ((float)$baseValueOpenFloat) {
            return $this->setBaseValueOpen(Money::EUR(100 * $baseValueOpenFloat));
        } else {
            return $this->setBaseValueOpen(Money::EUR(0));
        }
    }
}