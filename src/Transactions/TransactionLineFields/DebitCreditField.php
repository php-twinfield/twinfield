<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

use PhpTwinfield\Enums\DebitCredit;

trait DebitCreditField
{
    /**
     * @var DebitCredit
     */
    private $debitCredit;

    /**
     * @return DebitCredit
     */
    public function getDebitCredit(): DebitCredit
    {
        return $this->debitCredit;
    }

    /**
     * @param DebitCredit $debitCredit
     * @return $this
     */
    public function setDebitCredit(DebitCredit $debitCredit): self
    {
        $this->debitCredit = $debitCredit;
        return $this;
    }
}