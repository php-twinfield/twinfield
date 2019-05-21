<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatTotalField
{
    /**
     * Vat total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatTotal;

    /**
     * @return Money|null
     */
    public function getVatTotal(): ?Money
    {
        return $this->vatTotal;
    }

    /**
     * @return float|null
     */
    public function getVatTotalToFloat(): ?float
    {
        if ($this->getVatTotal() != null) {
            return Util::formatMoney($this->getVatTotal());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatTotal
     * @return $this
     */
    public function setVatTotal(?Money $vatTotal)
    {
        $this->vatTotal = $vatTotal;

        return $this;
    }

    /**
     * @param float|null $vatTotalFloat
     * @return $this
     * @throws Exception
     */
    public function setVatTotalFromFloat(?float $vatTotalFloat)
    {
        if ((float)$vatTotalFloat) {
            return $this->setVatTotal(Money::EUR(100 * $vatTotalFloat));
        } else {
            return $this->setVatTotal(Money::EUR(0));
        }
    }
}