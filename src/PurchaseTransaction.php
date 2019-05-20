<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\DueDateField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberRaiseWarningField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;
use PhpTwinfield\PurchaseTransactionLine;

/*
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions
 */
class PurchaseTransaction extends BaseTransaction
{
    use DueDateField;
    use InvoiceNumberField;
    use InvoiceNumberRaiseWarningField;
    use PaymentReferenceField;

    /*
     * @return string
     */
    public function getLineClassName(): string
    {
        return PurchaseTransactionLine::class;
    }

    /*
     * @param PurchaseTransactionLine $line
     * @return $this
     */
    public function addLine(PurchaseTransactionLine $line)
    {
        parent::addLine($line);

        return $this;
    }
}
