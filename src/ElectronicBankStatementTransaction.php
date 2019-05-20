<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Fields\Dim1Field;
use PhpTwinfield\Fields\Dim2Field;
use PhpTwinfield\Fields\Dim3Field;
use PhpTwinfield\Fields\Dim4Field;
use PhpTwinfield\Fields\Transaction\TransactionLine\ValueFields;

class ElectronicBankStatementTransaction
{
    use Dim1Field;
    use Dim2Field;
    use Dim3Field;
    use Dim4Field;
    use ValueFields;

    /**
     * Contra account number in BBAN format. Either use contraAccount or contraIban or leave empty.
     *
     * @var string
     */
    private $contraAccount;

    /**
     * Contra account number in IBAN format. Either use contraAccount or contraIban or leave empty.
     *
     * @var string
     */
    private $contraIban;

    /**
     * @var string
     */
    private $description;

    /**
     * Reference for own use.
     *
     * @var string
     */
    private $reference;

    /**
     * Transaction type code.
     *
     * @var string
     */
    private $type;

    public function getContraAccount(): ?string
    {
        return $this->contraAccount;
    }

    public function setContraAccount(string $contraAccount): void
    {
        $this->contraAccount = $contraAccount;
        $this->contraIban = null;
    }

    public function getContraIban(): ?string
    {
        return $this->contraIban;
    }

    public function setContraIban(string $contraIban): void
    {
        $this->contraIban = $contraIban;
        $this->contraAccount = null;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getLineType(): ?LineType
    {
        /*
         * Electronic bank statement transactions don't have line types.
         */
        return null;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
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
