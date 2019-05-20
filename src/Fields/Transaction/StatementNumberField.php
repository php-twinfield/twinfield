<?php

namespace PhpTwinfield\Fields\Transaction;

trait StatementNumberField
{
    /**
     * Statement number field
     * Used by: BankTransaction, CashTransaction, ElectronicBankStatement
     *
     * @var int|null
     */
    private $statementNumber;

    /**
     * @return null|int
     */
    public function getStatementNumber(): ?int
    {
        return $this->statementNumber;
    }

    /**
     * @param null|int $statementNumber
     * @return $this
     */
    public function setStatementNumber(?int $statementNumber): self
    {
        $this->statementNumber = $statementNumber;
        return $this;
    }
}