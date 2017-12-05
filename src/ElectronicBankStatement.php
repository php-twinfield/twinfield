<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use Webmozart\Assert\Assert;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements
 */
class ElectronicBankStatement
{
    use StartAndCloseValueFields;

    /**
     * Optional attribute to indicate whether duplicates may be imported or not.
     *
     * @var bool
     */
    private $importDuplicate = false;

    /**
     * Contains the bank statement transactions.
     *
     * @var array
     */
    private $transactions = [];

    /**
     * Account number. Either account or iban or code should be set.
     *
     * @var ?string
     */
    private $account;

    /**
     * IBAN account number. Either account or iban or code should be set.
     *
     * @var ?string
     */
    private $iban;

    /**
     * Code of the corresponding bank book. Either account or iban or code should be set.
     *
     * @var ?string
     */
    private $code;

    /**
     * Bank statement date. Set to the current date when left empty.
     *
     * @var \DateTimeInterface
     */
    private $date;

    /**
     * Number of the bank statement. When left empty, last available bank statement number increased by one.
     *
     * @var int
     */
    private $statementnumber;

    /**
     * Optional. Office in which the bank statement should be imported.
     *
     * @var Office
     */
    private $office;

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
        $this->iban = null;
        $this->code = null;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): void
    {
        $this->iban = $iban;
        $this->account = null;
        $this->code = null;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
        $this->account = null;
        $this->iban = null;
    }

    /**
     * @return bool
     */
    public function isImportDuplicate(): bool
    {
        return $this->importDuplicate;
    }

    public function setImportDuplicate(bool $importDuplicate): void
    {
        $this->importDuplicate = $importDuplicate;
    }

    /**
     * @return array|ElectronicBankStatementTransaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param array|ElectronicBankStatementTransaction[] $transactions
     */
    public function setTransactions(array $transactions): void
    {
        Assert::allIsInstanceOf($transactions, ElectronicBankStatementTransaction::class);
        Assert::notEmpty($this->startvalue);

        $this->transactions = $transactions;

        $this->closevalue = $this->startvalue;

        foreach ($transactions as $transaction) {
            $this->closevalue = $this->closevalue->add($transaction->getValue());
        }
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getStatementnumber(): int
    {
        return $this->statementnumber;
    }

    public function setStatementnumber(int $statementnumber): void
    {
        $this->statementnumber = $statementnumber;
    }

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function setOffice(Office $office): void
    {
        $this->office = $office;
    }
}