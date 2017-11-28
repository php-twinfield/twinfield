<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;

/**
 * @todo $currencyDate Only if line type is detail. The line date.
 */
class JournalTransactionLine extends BaseTransactionLine
{
    use PerformanceFields;

    /**
     * @var string|null The invoice number. Only if line type is detail.
     */
    private $invoiceNumber;

    public function setType(?string $type): BaseTransactionLine
    {
        // Only 'detail' and 'vat' are supported.
        if ($type == self::TYPE_TOTAL) {
            throw Exception::invalidLineTypeForTransaction($type, $this);
        }

        return parent::setType($type);
    }

    /**
     * If line type = detail the journal balance account or profit and loss account.
     *
     * If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account
     * will be taken as entered at the VAT code in Twinfield.
     *
     * @param string|null $dim1
     * @return JournalTransactionLine
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
     * @return JournalTransactionLine
     * @throws Exception
     */
    public function setDim2(?string $dim2): BaseTransactionLine
    {
        if ($dim2 !== null && $this->getType() == self::TYPE_VAT) {
            throw Exception::invalidDimensionForLineType(2, $this->getType());
        }

        return parent::setDim2($dim2);
    }

    /**
     * If line type = detail amount without VAT.
     *
     * If line type = vat VAT amount.
     *
     * @param float|null $value
     * @return JournalTransactionLine
     */
    public function setValue(?float $value): BaseTransactionLine
    {
        return parent::setValue($value);
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): BaseTransactionLine
    {
        if ($invoiceNumber !== null && $this->getType() != self::TYPE_DETAIL) {
            throw Exception::invalidFieldForLineType('invoiceNumber', $this);
        }

        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
    /**
     * Payment status of the journal transaction. If line type vat always notmatchable. Read-only attribute.
     *
     * @param string|null $matchStatus
     * @return JournalTransactionLine
     * @throws Exception
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        if (
            $matchStatus !== null &&
            $this->getType() == self::TYPE_VAT &&
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
     * @return JournalTransactionLine
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): BaseTransactionLine
    {
        if ($matchLevel !== null && $this->getType() != self::TYPE_DETAIL) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /**
     * Only if line type is detail. The amount still owed in base currency. Read-only attribute.
     *
     * @param float|null $baseValueOpen
     * @return JournalTransactionLine
     * @throws Exception
     */
    public function setBaseValueOpen(?float $baseValueOpen): BaseTransactionLine
    {
        if ($baseValueOpen !== null && $this->getType() != self::TYPE_DETAIL) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }
}
