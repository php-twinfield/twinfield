<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionLineFields\MatchDateField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueOpenField;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use Webmozart\Assert\Assert;

class SalesTransactionLine extends BaseTransactionLine
{
    use MatchDateField;
    use VatTotalFields;
    use ValueOpenField;
    use PerformanceFields;
    use FreeTextFields;

    /**
     * @var SalesTransaction
     */
    private $transaction;

    /**
     * @param SalesTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, "Attempting to set a transaction while the transaction is already set.");
        Assert::isInstanceOf($object, SalesTransaction::class);
        $this->transaction = $object;
    }

    /**
     * References the transaction this line belongs too.
     *
     * @return SalesTransaction
     */
    public function getTransaction(): SalesTransaction
    {
        return $this->transaction;
    }

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
        if ($dim2 !== null && $this->getLineType()->equals(LineType::VAT())) {
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
     * Payment status of the sales transaction. If line type detail or vat always notmatchable, according to the documentation.
     * However, in practice this appears to be untrue for detail, see issue #177. Read-only attribute.
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
     * Only if line type is total. The level of the matchable dimension. Read-only attribute.
     *
     * @param int|null $matchLevel
     * @return $this
     * @throws Exception
     */
    public function setMatchLevel(?int $matchLevel): BaseTransactionLine
    {
        if ($matchLevel !== null && $this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('matchLevel', $this);
        }

        return parent::setMatchLevel($matchLevel);
    }

    /**
     * Only if line type is total. The amount still owed in base currency. Read-only attribute.
     *
     * @param Money|null $baseValueOpen
     * @return $this
     * @throws Exception
     */
    public function setBaseValueOpen(?Money $baseValueOpen): BaseTransactionLine
    {
        if ($baseValueOpen !== null && $this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('baseValueOpen', $this);
        }

        return parent::setBaseValueOpen($baseValueOpen);
    }

    /**
     * If line type = detail the project or asset or empty.
     *
     * @param string $dim3
     * @return $this
     * @throws Exception
     */
    public function setProjectAsset(string $dim3)
    {
        if (!$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidDimensionForLineType(3, $this);
        }

        return $this->setDim3($dim3);
    }

    /**
     * Only if line type is vat. Amount on which VAT was calculated in the currency of the sales transaction.
     *
     * @param Money|null $vatTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatTurnover(?Money $vatTurnover)
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType("vatturnover", $this);
        }
        return parent::setVatTurnOver($vatTurnover);
    }

    /**
     * Only if line type is vat. Amount on which VAT was calculated in base currency.
     *
     * @param Money|null $vatBaseTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatBaseTurnover(?Money $vatBaseTurnover)
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType("vatbaseturnover", $this);
        }
        return parent::setVatBaseTurnover($vatBaseTurnover);
    }

    /**
     * Only if line type is vat. Amount on which VAT was calculated in reporting currency.
     *
     * @param Money|null $vatRepTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatRepTurnover(?Money $vatRepTurnover)
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType("vatrepturnover", $this);
        }
        return parent::setVatRepTurnover($vatRepTurnover);
    }

    /**
     * Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
     *
     * @param int|null $baseline
     * @return $this
     * @throws Exception
     */
    public function setBaseline(?int $baseline)
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType("baseline", $this);
        }
        return parent::setBaseline($baseline);
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
