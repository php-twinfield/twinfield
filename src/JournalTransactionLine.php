<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use Webmozart\Assert\Assert;

/**
 * @todo $currencyDate Only if line type is detail. The line date.
 */
class JournalTransactionLine extends BaseTransactionLine
{
    use PerformanceFields;
    use InvoiceNumberField {
        setInvoiceNumber as traitSetInvoiceNumber;
    }

    /**
     * @var JournalTransaction
     */
    private $transaction;

    /**
     * @param JournalTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, "Attempting to set a transaction while the transaction is already set.");
        Assert::isInstanceOf($object, JournalTransaction::class);
        $this->transaction = $object;
    }

    /**
     * References the transaction this line belongs too.
     *
     * @return JournalTransaction
     */
    public function getTransaction(): JournalTransaction
    {
        return $this->transaction;
    }

    /**
     * @param LineType $lineType
     * @return $this
     * @throws Exception
     */
    public function setLineType(LineType $lineType): BaseTransactionLine
    {
        // Only 'detail' and 'vat' are supported.
        if ($lineType->equals(LineType::TOTAL())) {
            throw Exception::invalidLineTypeForTransaction($lineType, $this);
        }

        return parent::setLineType($lineType);
    }

    /**
     * If line type = detail the journal balance account or profit and loss account.
     *
     * If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account
     * will be taken as entered at the VAT code in Twinfield.
     *
     * @param string|null $dim1
     * @return $this
     */
    public function setDim1(?string $dim1): BaseTransactionLine
    {
        return parent::setDim1($dim1);
    }

    /**
     * If line type = detail the customer or supplier or the cost center or empty.
     *
     * If line type = vat empty.
     *
     * @param string|null $dim2
     * @return $this
     * @throws Exception
     */
    public function setDim2(?string $dim2): BaseTransactionLine
    {
        if ($dim2 !== null && $this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidDimensionForLineType(2, $this);
        }

        return parent::setDim2($dim2);
    }

    /**
     * If line type = detail amount without VAT.
     *
     * If line type = vat VAT amount.
     *
     * @param Money $value
     * @return $this
     */
    public function setValue(Money $value): BaseTransactionLine
    {
        parent::setValue($value);
        return $this;
    }

    /**
     * @param string|null $invoiceNumber
     * @return $this
     * @throws Exception
     */
    public function setInvoiceNumber(?string $invoiceNumber): BaseTransactionLine
    {
        if ($invoiceNumber !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('invoiceNumber', $this);
        }

        return $this->traitSetInvoiceNumber($invoiceNumber);
    }

    /**
     * Payment status of the journal transaction. If line type vat always notmatchable. Read-only attribute.
     *
     * @param string|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        if (
            $matchStatus !== null &&
            $this->getLineType()->equals(LineType::VAT()) &&
            $matchStatus != self::MATCHSTATUS_NOTMATCHABLE
        ) {
            throw Exception::invalidMatchStatusForLineType($matchStatus, $this);
        }

        return parent::setMatchStatus($matchStatus);
    }

    /**
     * Only if line type is detail. The level of the matchable dimension. Read-only attribute.
     *
     * @param int|null $matchLevel
     * @return $this
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): BaseTransactionLine
    {
        if ($matchLevel !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /**
     * Only if line type is detail. The amount still owed in base currency. Read-only attribute.
     *
     * @param Money|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?Money $baseValueOpen): BaseTransactionLine
    {
        if ($baseValueOpen !== null && !$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }

    /**
     * Returns true if a positive amount in the TOTAL line means the amount is 'debit'. Examples of incoming transaction
     * types are Sales Transactions, Electronic Bank Statements and Bank Transactions.
     *
     * Returns false if a positive amount in the TOTAL line means the amount is 'credit'. An example of an outgoing
     * transaction type is a Purchase Transaction.
     *
     * @return bool
     */
    protected function isIncomingTransactionType(): bool
    {
        return true;
    }
}
