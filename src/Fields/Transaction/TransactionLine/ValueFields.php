<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use Webmozart\Assert\Assert;

trait ValueFields
{
    /**
     * @var DebitCredit
     */
    private $debitCredit;

    /**
     * @var Money
     */
    private $value;

    abstract public function getLineType(): ?LineType;

    /**
     * Returns true if a positive amount in the TOTAL line means the amount is 'debit'. Examples of incoming transaction
     * types are Sales Transactions, Electronic Bank Statements and Bank Transactions.
     *
     * Returns false if a positive amount in the TOTAL line means the amount is 'credit'. An example of an outgoing
     * transaction type is a Purchase Transaction.
     *
     * @return bool
     */
    abstract protected function isIncomingTransactionType(): bool;

    /**
     * @return DebitCredit
     */
    public function getDebitCredit(): DebitCredit
    {
        return $this->debitCredit ?? DebitCredit::CREDIT();
    }

    /**
     * Note: you must first read the value.
     *
     * @param DebitCredit $debitCredit
     * @return $this
     */
    public function setDebitCredit(DebitCredit $debitCredit)
    {
        Assert::notEmpty($this->value);
        $this->debitCredit = $debitCredit;

        if ($debitCredit->equals(DebitCredit::CREDIT())) {
            $positiveValue = true;
        } else {
            $positiveValue = false;
        }


        if (!$this->isIncomingTransactionType()) {
            $positiveValue = !$positiveValue;
        }

        if (!empty($this->getLineType()) && $this->getLineType()->equals(LineType::TOTAL())) {
            $positiveValue = !$positiveValue;
        }

        if ($positiveValue) {
            $this->value = $this->value->absolute();
        } else {
            $this->value = $this->value->absolute()->negative();
        }

        return $this;
    }

    public function getValue(): Money
    {
        /*
         * Always return the absolute value, Twinfield uses "debitcredit" fields instead of signs.
         */
        return $this->value->absolute();
    }

    /**
     * @return float|null
     */
    public function getValueToFloat(): ?float
    {
        if ($this->getValue() != null) {
            return Util::formatMoney($this->getValue());
        } else {
            return 0;
        }
    }

    public function getSignedValue(): Money
    {
        return $this->value;
    }

    public function setValue(Money $value)
    {
        if ($value->isPositive()) {
            $debitCredit = DebitCredit::CREDIT();
        } else {
            $debitCredit = DebitCredit::DEBIT();
        }

        if (!$this->isIncomingTransactionType()) {
            $debitCredit = $debitCredit->invert();
        }

        if (!empty($this->getLineType()) && $this->getLineType()->equals(LineType::TOTAL())) {
            $debitCredit = $debitCredit->invert();
        }

        $this->debitCredit = $debitCredit;

        /*
         * Keep sign info here.
         */
        $this->value = $value;
        return $this;
    }

    /**
     * @param float|null $valueFloat
     * @return $this
     * @throws Exception
     */
    public function setValueFromFloat(?float $valueFloat)
    {
        if ((float)$valueFloat) {
            return $this->setValue(Money::EUR(100 * $valueFloat));
        } else {
            return $this->setValue(Money::EUR(0));
        }
    }
}