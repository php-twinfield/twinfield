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

    public function getStatementnumber(): ?int
    {
        return $this->statementnumber;
    }

    /**
     * @param int $statementnumber
     * @return $this
     */
    public function setStatementnumber($statementnumber)
    {
        $this->statementnumber = $statementnumber;
        return $this;
    }
}
