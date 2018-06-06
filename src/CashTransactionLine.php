<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\InvoiceNumberField;
use PhpTwinfield\Transactions\TransactionLineFields\PerformanceFields;
use PhpTwinfield\Transactions\TransactionLineFields\VatTotalFields;
use Webmozart\Assert\Assert;

class CashTransactionLine extends BaseTransactionLine
{
    use VatTotalFields;
    use PerformanceFields;
    use InvoiceNumberField {
        setInvoiceNumber as traitSetInvoiceNumber;
    }

    /**
     * @var CashTransaction
     */
    private $transaction;

    /**
     * @param CashTransaction $object
     */
    public function setTransaction($object): void
    {
        Assert::null($this->transaction, 'Attempting to set a transaction while the transaction is already set.');
        Assert::isInstanceOf($object, CashTransaction::class);
        $this->transaction = $object;
    }

    /**
     * References the transaction this line belongs too.
     *
     * @return CashTransaction
     */
    public function getTransaction(): CashTransaction
    {
        return $this->transaction;
    }

    /**
     * If line type = total the cash balance account.
     *
     * If line type = detail the customer or supplier balance account or profit and loss account.
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
     * If line type = total empty.
     *
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
        if ($dim2 !== null &&
            ($this->getLineType()->equals(LineType::VAT()) || $this->getLineType()->equals(LineType::TOTAL()))) {
            throw Exception::invalidDimensionForLineType(2, $this);
        }

        return parent::setDim2($dim2);
    }

    /**
     * If line type = total empty.
     *
     * If line type = detail the project or asset or empty.
     *
     * If line type = vat empty.
     *
     * @param string|null $dim3
     * @return $this
     * @throws Exception
     */
    public function setDim3(?string $dim3): BaseTransactionLine
    {
        if ($dim3 !== null &&
            ($this->getLineType()->equals(LineType::VAT()) || $this->getLineType()->equals(LineType::TOTAL()))) {
            throw Exception::invalidDimensionForLineType(3, $this);
        }

        return parent::setDim3($dim3);
    }

    /**
     * If line type = total, based on the sum of the individual cash transaction lines.
     * - In case of a cash addition debit.
     * - In case of a cash withdrawal credit.
     *
     * If line type = detail
     * - In case money is received credit.
     * - In case money is paid debit.
     *
     * If line type = vat, based on the sum of the vat amounts of the individual cash transaction lines.
     * - In case of a cash addition credit.
     * - In case of a cash withdrawal debit.
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
     * Payment status of the cash transaction. If line type total or vat always notmatchable. Read-only attribute.
     *
     * @param string|null $matchStatus
     * @return $this
     * @throws Exception
     */
    public function setMatchStatus(?string $matchStatus): BaseTransactionLine
    {
        if (
            $matchStatus !== null &&
            in_array($this->getLineType(), [LineType::TOTAL(), LineType::VAT()]) &&
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
     * Only if line type is vat. Amount on which VAT was calculated in the currency of the sales transaction.
     *
     * @param Money|null $vatTurnover
     * @return $this
     * @throws Exception
     */
    public function setVatTurnover(?Money $vatTurnover): BaseTransactionLine
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatturnover', $this);
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
    public function setVatBaseTurnover(?Money $vatBaseTurnover): BaseTransactionLine
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatbaseturnover', $this);
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
    public function setVatRepTurnover(?Money $vatRepTurnover): BaseTransactionLine
    {
        if (!$this->getLineType()->equals(LineType::VAT())) {
            throw Exception::invalidFieldForLineType('vatrepturnover', $this);
        }
        return parent::setVatRepTurnover($vatRepTurnover);
    }

    /**
     * @param string|null $invoiceNumber
     * @return $this
     * @throws Exception
     */
    public function setInvoiceNumber(?string $invoiceNumber)
    {
        if (!$this->getLineType()->equals(LineType::DETAIL())) {
            throw Exception::invalidFieldForLineType('invoicenumber', $this);
        }

        return $this->traitSetInvoiceNumber($invoiceNumber);
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
