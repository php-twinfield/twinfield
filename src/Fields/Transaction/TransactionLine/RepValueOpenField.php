<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait RepValueOpenField
{
    /**
     * Rep value open field
     * Used by: RepTransactionLine
     *
     * @var Money|null
     */
    private $repValueOpen;

    /**
     * @return Money|null
     */
    public function getRepValueOpen(): ?Money
    {
        return $this->repValueOpen;
    }

    /**
     * @return float|null
     */
    public function getRepValueOpenToFloat(): ?float
    {
        if ($this->getRepValueOpen() != null) {
            return Util::formatMoney($this->getRepValueOpen());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $repValueOpen
     * @return $this
     */
    public function setRepValueOpen(?Money $repValueOpen)
    {
        $this->repValueOpen = $repValueOpen;

        return $this;
    }

    /**
     * @param float|null $repValueOpenFloat
     * @return $this
     * @throws Exception
     */
    public function setRepValueOpenFromFloat(?float $repValueOpenFloat)
    {
        if ((float)$repValueOpenFloat) {
            return $this->setRepValueOpen(Money::EUR(100 * $repValueOpenFloat));
        } else {
            return $this->setRepValueOpen(Money::EUR(0));
        }
    }
}