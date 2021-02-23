<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use Webmozart\Assert\Assert;

/**
 * @todo $matchDate Only if line type is total. The date on which the purchase invoice is matched. Read-only attribute.
 */
class PurchaseTransactionLine extends BaseTransactionLine
{
    use VatTotalFields;
    use ValueOpenField;

    /**
     * @var PurchaseTransaction
     */
    private $transaction;

    /**
     * @param PurchaseTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, "Attempting to set a transaction while the transaction is already set.");
        Assert::isInstanceOf($object, PurchaseTransaction::class);
        $this->transaction = $object;
    }

    /**
     * References the transaction this line belongs too.
     *
     * @return PurchaseTransaction
     */
    public function getTransaction(): PurchaseTransaction
    {
        return $this->transaction;
    }

    /**
     * If line type = total the accounts payable balance account. When dim1 is omitted, by default the general ledger
     * account will be taken as entered at the supplier in Twinfield.
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
     * If line type = total the account payable.
     *
     * If line type = detail the cost center or empty.
     *
     * If line type = vat the cost center or empty.
     *
     * @param string|null $dim2
     * @return $this
     */
    public function setDim2(?string $dim2): BaseTransactionLine
    {
        return parent::setDim2($dim2);
    }

    /**
     * If line type = total
     * - In case of a 'normal' purchase transaction credit.
     * - In case of a credit purchase transaction debit.
     *
     * If line type = detail or vat
     * - In case of a 'normal' purchase transaction debit.
     * - In case of a credit purchase transaction credit.
     *
     * @param DebitCredit $debitCredit
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
     * Payment status of the purchase transaction. If line type detail or vat always notmatchable. Read-only attribute.
     *
     * @param string|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        if (
            $matchStatus !== null &&
            in_array($this->getLineType(), [LineType::DETAIL(), LineType::VAT()]) &&
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
        if ($matchLevel !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /**
     * Only if line type is total. The amount still to be paid in base currency. Read-only attribute.
     *
     * @param Money|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?Money $baseValueOpen): BaseTransactionLine
    {
        if ($baseValueOpen !== null && !$this->getLineType()->equals(LineType::TOTAL())) {
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
        return false;
    }
}
