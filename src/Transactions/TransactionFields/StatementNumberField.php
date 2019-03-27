<?php

namespace PhpTwinfield\Transactions\TransactionFields;

trait StatementNumberField
{
    /**
     * Number of the bank statement. When left empty, last available bank statement number increased by one.
     *
     * Or: Number of the bank statement. Don't confuse this number with the transaction number.
     *
     * @var int|null
     */
    private $statementnumber;

    public function getStatementnumber()
    {
        return $this->statementnumber;
    }

    /**
     * @param int $statementnumber
     * @return $this
     */
    public function setStatementnumber(int $statementnumber)
    {
        $this->statementnumber = $statementnumber;
        return $this;
    }
}