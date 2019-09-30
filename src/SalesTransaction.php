<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionFields\PaymentReferenceField;
use PhpTwinfield\Transactions\TransactionLineFields\ThreeDimFields;

/**
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions
 */
class SalesTransaction extends BaseTransaction
{
    use InvoiceNumberField;
    use PaymentReferenceField;
    use ThreeDimFields;

    /**
     * @var string|null The sales transaction origin reference (id). Provided in form of Guid. Read-only attribute.
     *                  Sample: "f386393c-e4ba-439a-add4-3b366535d7bf".
     */
    private $originReference;

    /**
     * @return string
     */
    public function getLineClassName(): string
    {
        return SalesTransactionLine::class;
    }

    /**
     * @return string|null
     */
    public function getOriginReference(): ?string
    {
        return $this->originReference;
    }

    /**
     * @param string|null $originReference
     * @return $this
     */
    public function setOriginReference(?string $originReference): SalesTransaction
    {
        $this->originReference = $originReference;

        return $this;
    }

    /**
     * When creating a new sales transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a sales transaction, the related transaction number should be provided.
     *
     * @param int|null $number
     * @return $this
     */
    public function setNumber(?int $number): BaseTransaction
    {
        return parent::setNumber($number);
    }
}
