<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatTurnoverField
{
    /**
     * Vat turnover field
     * Used by: BaseTransactionLine
     *
     * @var Money|null
     */
    private $vatTurnover;

    /**
     * @return Money|null
     */
    public function getVatTurnover(): ?Money
    {
        return $this->vatTurnover;
    }

    /**
     * @return float|null
     */
    public function getVatTurnoverToFloat(): ?float
    {
        if ($this->getVatTurnover() != null) {
            return Util::formatMoney($this->getVatTurnover());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatTurnover
     * @return $this
     */
    public function setVatTurnover(?Money $vatTurnover)
    {
        $this->vatTurnover = $vatTurnover;

        return $this;
    }

    /**
     * @param float|null $vatTurnoverFloat
     * @return $this
     * @throws Exception
     */
    public function setVatTurnoverFromFloat(?float $vatTurnoverFloat)
    {
        if ((float)$vatTurnoverFloat) {
            return $this->setVatTurnover(Money::EUR(100 * $vatTurnoverFloat));
        } else {
            return $this->setVatTurnover(Money::EUR(0));
        }
    }
}