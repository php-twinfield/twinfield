<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\DueDateField;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions
 */
class SalesTransaction extends BaseTransaction
{
    use DueDateField;
    use InvoiceNumberField;
    use PaymentReferenceField;

    /**
     * @var string The sales transaction origin reference (id). Provided in form of Guid. Read-only attribute.
     *             Sample: "f386393c-e4ba-439a-add4-3b366535d7bf".
     */
    private $originReference;

    public function getLineClassName(): string
    {
        return SalesTransactionLine::class;
    }

    public function getOriginReference(): string
    {
        return $this->originReference;
    }

    public function setOriginReference(string $originReference): BaseTransaction
    {
        $this->originReference = $originReference;

        return $this;
    }

    /**
     * When creating a new sales transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a sales transaction, the related transaction number should be provided.
     *
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number): BaseTransaction
    {
        return parent::setNumber($number);
    }
}
