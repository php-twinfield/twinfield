<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatRepTotalField
{
    /**
     * Vat rep total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatRepTotal;

    /**
     * @return Money|null
     */
    public function getVatRepTotal(): ?Money
    {
        return $this->vatRepTotal;
    }

    /**
     * @return float|null
     */
    public function getVatRepTotalToFloat(): ?float
    {
        if ($this->getVatRepTotal() != null) {
            return Util::formatMoney($this->getVatRepTotal());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatRepTotal
     * @return $this
     */
    public function setVatRepTotal(?Money $vatRepTotal)
    {
        $this->vatRepTotal = $vatRepTotal;

        return $this;
    }

    /**
     * @param float|null $vatRepTotalFloat
     * @return $this
     * @throws Exception
     */
    public function setVatRepTotalFromFloat(?float $vatRepTotalFloat)
    {
        if ((float)$vatRepTotalFloat) {
            return $this->setVatRepTotal(Money::EUR(100 * $vatRepTotalFloat));
        } else {
            return $this->setVatRepTotal(Money::EUR(0));
        }
    }
}