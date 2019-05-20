<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Util;

trait VatBaseTotalField
{
    /**
     * Vat base total field
     * Used by: BankTransactionLine, CashTransactionLine, PurchaseTransactionLine, SalesTransactionLine
     *
     * @var Money|null
     */
    private $vatBaseTotal;

    /**
     * @return Money|null
     */
    public function getVatBaseTotal(): ?Money
    {
        return $this->vatBaseTotal;
    }

    /**
     * @return float|null
     */
    public function getVatBaseTotalToFloat(): ?float
    {
        if ($this->getVatBaseTotal() != null) {
            return Util::formatMoney($this->getVatBaseTotal());
        } else {
            return 0;
        }
    }

    /**
     * @param Money|null $vatBaseTotal
     * @return $this
     */
    public function setVatBaseTotal(?Money $vatBaseTotal)
    {
        $this->vatBaseTotal = $vatBaseTotal;

        return $this;
    }

    /**
     * @param float|null $vatBaseTotalFloat
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTotalFromFloat(?float $vatBaseTotalFloat)
    {
        if ((float)$vatBaseTotalFloat) {
            return $this->setVatBaseTotal(Money::EUR(100 * $vatBaseTotalFloat));
        } else {
            return $this->setVatBaseTotal(Money::EUR(0));
        }
    }
}