<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;

/**
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions
 */
class PurchaseTransaction extends BaseTransaction
{
    use DueDateField;
    use InvoiceNumberField;
    use PaymentReferenceField;

    /**
     * @return string
     */
    public function getLineClassName(): string
    {
        return PurchaseTransactionLine::class;
    }
}
