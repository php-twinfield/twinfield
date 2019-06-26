<?php

namespace PhpTwinfield\Fields\Invoice;

use Money\Money;
use PhpTwinfield\Util;

trait InvoiceAmountField
{
    /**
     * Invoice amount field
     * Used by: Invoice
     *
     * @var Money|null
     */
    private $invoiceAmount;

    /**
     * @return Money|null
     */
    public function getInvoiceAmount(): ?Money
    {
        return $this->invoiceAmount;
    }
    
    /**
     * @param Money|null $invoiceAmount
     * @return $this
     */
    public function setInvoiceAmount(?Money $invoiceAmount)
    {
        $this->invoiceAmount = $invoiceAmount;

        return $this;
    }

    /**
     * @param float|null $invoiceAmountFloat
     * @return $this
     * @throws Exception
     */
    public function setInvoiceAmountFromFloat(?float $invoiceAmountFloat)
    {
        if ((float)$invoiceAmountFloat) {
            return $this->setInvoiceAmount(Money::EUR(100 * $invoiceAmountFloat));
        } else {
            return $this->setInvoiceAmount(Money::EUR(0));
        }
    }
}