<?php

namespace PhpTwinfield;

use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Transactions\TransactionLineFields\ValueFields;

class ElectronicBankStatementTransaction
{
    use ValueFields;

    /**
     * Contra account number in BBAN format. Either use contraaccount or contraiban or leave empty.
     *
     * @var string
     */
    public $contraaccount;
    /**
     * Contra account number in IBAN format. Either use contraaccount or contraiban or leave empty.
     *
     * @var string
     */
    public $contraiban;
    /**
     * Transaction type code.
     *
     * @var string
     */
    public $type;

    /**
     * Reference for own use.
     *
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * Dimension 1.
     * Read-only attribute.
     *
     * @var string
     */
    public $dim1;

    /**
     * Dimension 2.
     * Read-only attribute.
     *
     * @var string
     */
    public $dim2;

    /**
     * Dimension 3.
     * Read-only attribute.
     *
     * @var string
     */
    public $dim3;

    /**
     * Dimension 4.
     * Read-only attribute.
     *
     * @var string
     */
    public $dim4;

    public function getContraaccount(): ?string
    {
        return $this->contraaccount;
    }

    public function setContraaccount(string $contraaccount): void
    {
        $this->contraaccount = $contraaccount;
        $this->contraiban = null;
    }

    public function getContraiban(): ?string
    {
        return $this->contraiban;
    }

    public function setContraiban(string $contraiban): void
    {
        $this->contraiban = $contraiban;
        $this->contraaccount = null;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDim1(): string
    {
        return $this->dim1;
    }

    public function getDim2(): string
    {
        return $this->dim2;
    }

    public function getDim3(): string
    {
        return $this->dim3;
    }

    public function getDim4(): string
    {
        return $this->dim4;
    }
}
