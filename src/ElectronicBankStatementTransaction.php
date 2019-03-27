<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionLineFields\FourDimFields;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;

class ElectronicBankStatementTransaction
{
    use ValueFields;
    use FourDimFields;

    /**
     * Contra account number in BBAN format. Either use contraaccount or contraiban or leave empty.
     *
     * @var string
     */
    private $contraaccount;
    /**
     * Contra account number in IBAN format. Either use contraaccount or contraiban or leave empty.
     *
     * @var string
     */
    private $contraiban;
    /**
     * Transaction type code.
     *
     * @var string
     */
    private $type;

    /**
     * Reference for own use.
     *
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $description;

    public function getContraaccount()
    {
        return $this->contraaccount;
    }

    public function setContraaccount(string $contraaccount)
    {
        $this->contraaccount = $contraaccount;
        $this->contraiban = null;
    }

    public function getContraiban()
    {
        return $this->contraiban;
    }

    public function setContraiban(string $contraiban)
    {
        $this->contraiban = $contraiban;
        $this->contraaccount = null;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getLineType()
    {
        /*
         * Electronic bank statement transactions don't have line types.
         */
        return null;
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
