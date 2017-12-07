<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;

class SalesTransactionLine extends BaseTransactionLine
{
    use VatTotalFields;
    use ValueOpenField;
    use PerformanceFields;

    /**
     * If line type = total the accounts receivable balance account. When dim1 is omitted, by default the general ledger
     * account will be taken as entered at the customer in Twinfield.
     *
     * If line type = detail the profit and loss account.
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
     * If line type = total the account receivable.
     *
     * If line type = detail the cost center or empty.
     *
     * If line type = vat empty.
     *
     * @param string|null $dim2
     * @return $this
     * @throws Exception
     */
    public function setDim2(?string $dim2): BaseTransactionLine
    {
        if ($dim2 !== null && $this->getType() == LineType::VAT()) {
            throw Exception::invalidDimensionForLineType(2, $this);
        }

        return parent::setDim2($dim2);
    }

    /**
     * If line type = total
     * - In case of a 'normal' sales transaction debit.
     * - In case of a credit sales transaction credit.
     *
     * If line type = detail or vat
     * - In case of a 'normal' sales transaction credit.
     * - In case of a credit sales transaction debit.
     *
     * @param DebitCredit::DEBIT() $debitCredit
     * @return $this
     */
    public function setDebitCredit(DebitCredit $debitCredit): BaseTransactionLine
    {
        return parent::setDebitCredit($debitCredit);
    }

    /**
     * If line type = total amount including VAT.
     *
     * If line type = detail amount without VAT.
     *
     * If line type = vat VAT amount.
     *
     * @param Money $value
     * @return $this
     */
    public function setValue(Money $value): BaseTransactionLine
    {
        return parent::setValue($value);
    }

    /**
     * Payment status of the sales transaction. If line type detail or vat always notmatchable. Read-only attribute.
     *
     * @param string|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        if (
            $matchStatus !== null &&
            in_array($this->getType(), [LineType::DETAIL(), LineType::VAT()]) &&
            $matchStatus != self::MATCHSTATUS_NOTMATCHABLE
        ) {
            throw Exception::invalidMatchStatusForLineType($matchStatus, $this);
        }

        return parent::setMatchStatus($matchStatus);
    }

    /**
     * Only if line type is total. The level of the matchable dimension. Read-only attribute.
     *
     * @param int|null $matchLevel
     * @return $this
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): BaseTransactionLine
    {
        if ($matchLevel !== null && $this->getType() != LineType::TOTAL()) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /**
     * Only if line type is total. The amount still owed in base currency. Read-only attribute.
     *
     * @param float|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?float $baseValueOpen): BaseTransactionLine
    {
        if ($baseValueOpen !== null && $this->getType() != LineType::TOTAL()) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }
}
