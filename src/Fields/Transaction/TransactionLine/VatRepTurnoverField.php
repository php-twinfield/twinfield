<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatRepTurnoverField
{
    /**
     * Vat rep turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatRepTurnover;

    /**
     * @return Money|null
     */
    public function getVatRepTurnover(): ?Money
    {
        return $this->vatRepTurnover;
    }

    /**
     * @return float|null
     */
    public function getVatRepTurnoverToFloat(): ?float
    {
        if ($this->getVatRepTurnover() != null) {
            return Util::formatMoney($this->getVatRepTurnover());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatRepTurnover
     * @return $this
     */
    public function setVatRepTurnover(?Money $vatRepTurnover)
    {
        $this->vatRepTurnover = $vatRepTurnover;

        return $this;
    }

    /**
     * @param float|null $vatRepTurnoverFloat
     * @return $this
     * @throws Exception
     */
    public function setVatRepTurnoverFromFloat(?float $vatRepTurnoverFloat)
    {
        if ((float)$vatRepTurnoverFloat) {
            return $this->setVatRepTurnover(Money::EUR(100 * $vatRepTurnoverFloat));
        } else {
            return $this->setVatRepTurnover(Money::EUR(0));
        }
    }
}