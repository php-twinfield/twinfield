<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

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

        if (!empty($this->getLineType()) && $this->getLineType()->equals(LineType::TOTAL())) {
            /*
             * If line type = total
             * - In case of a 'normal' transaction `debit`.
             * - In case of a credit transaction `credit`.
             */
            if ($debitCredit->equals(DebitCredit::CREDIT())) {
                $this->value = $this->value->absolute()->negative();
            } else {
                $this->value = $this->value->absolute();
            }
        } else {
            /*
             * If line type = detail or vat
             * - In case of a 'normal' transaction `credit`.
             * - In case of a credit transaction `debit`.
             */
            if ($debitCredit->equals(DebitCredit::CREDIT())) {
                $this->value = $this->value->absolute();
            } else {
                $this->value = $this->value->absolute()->negative();
            }
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

    public function getSignedValue(): Money
    {
        return $this->value;
    }

    public function setValue(Money $value)
    {
        if (!empty($this->getLineType()) && $this->getLineType()->equals(LineType::TOTAL())) {
            /*
             * If line type = total
             * - In case of a 'normal' transaction `debit`.
             * - In case of a credit transaction `credit`.
             */
            if ($value->isPositive()) {
                $this->debitCredit = DebitCredit::DEBIT();
            } else {
                $this->debitCredit = DebitCredit::CREDIT();
            }
        } else {
            /*
             * If line type = detail or vat
             * - In case of a 'normal' transaction `credit`.
             * - In case of a credit transaction `debit`.
             */
            if ($value->isPositive()) {
                $this->debitCredit = DebitCredit::CREDIT();
            } else {
                $this->debitCredit = DebitCredit::DEBIT();
            }
        }

        /*
         * Keep sign info here.
         */
        $this->value = $value;
        return $this;
    }
}