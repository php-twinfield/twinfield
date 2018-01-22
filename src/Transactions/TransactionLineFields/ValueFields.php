<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
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
            $this->value = $this->value->absolute();
            return $this;
        }

        $this->value = $this->value->absolute()->negative();

        return $this;
    }

    public function getValue(): Money
    {
        /*
         * Always return the absolute value, Twinfield uses "debitcredit" fields instead of signs.
         */
        return $this->value->absolute();
    }

    public function setValue(Money $value)
    {
        if ($value->isPositive()) {
            $this->debitCredit = DebitCredit::CREDIT();
        } else {
            $this->debitCredit = DebitCredit::DEBIT();
        }

        /*
         * Keep sign info here.
         */
        $this->value = $value;
        return $this;
    }
}