<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\DueDateField;
use PhpTwinfield\Fields\InvoiceNumberField;
use PhpTwinfield\Fields\Transaction\InvoiceNumberRaiseWarningField;
use PhpTwinfield\Fields\Transaction\PaymentReferenceField;

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
}
