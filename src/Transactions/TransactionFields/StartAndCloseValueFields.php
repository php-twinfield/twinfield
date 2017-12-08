<?php

namespace PhpTwinfield\Transactions\TransactionFields;

use Money\Currency;
use Money\Money;

trait StartAndCloseValueFields
{
    /**
     * Currency code. Set to the currency of the corresponding bank day book when left empty.
     *
     * @var Currency
     */
    private $currency;

    /**
     * Opening balance. If not provided, the opening balance will be based on the previous bank statement.
     *
     * @var Money
     */
    private $startvalue;

    /**
     * Closing balance. If not provided, the closing balance will be based on the opening balance and the total amount of the transactions.
     *
     * @var Money
     */
    private $closevalue;


    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getStartvalue(): Money
    {
        return $this->startvalue;
    }

    public function setStartvalue(Money $startvalue): void
    {
        $this->currency   = $startvalue->getCurrency();
        $this->startvalue = $startvalue;
        $this->closevalue = $startvalue;
    }

    public function getClosevalue(): Money
    {
        return $this->closevalue;
    }
}