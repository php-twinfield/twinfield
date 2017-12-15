<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionLineFields\DateField;
use Webmozart\Assert\Assert;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements
 */
class ElectronicBankStatement
{
    use StartAndCloseValueFields;
    use DateField;
    use OfficeField;

    /**
     * Optional attribute to indicate whether duplicates may be imported or not.
     *
     * @var bool
     */
    public $importDuplicate = false;

    /**
     * Contains the bank statement transactions.
     *
     * @var array
     */
    public $transactions = [];

    /**
     * Account number. Either account or iban or code should be set.
     *
     * @var ?string
     */
    public $account;

    /**
     * IBAN account number. Either account or iban or code should be set.
     *
     * @var ?string
     */
    public $iban;

    /**
     * Code of the corresponding bank book. Either account or iban or code should be set.
     *
     * @var ?string
     */
    public $code;

    /**
     * Number of the bank statement. When left empty, last available bank statement number increased by one.
     *
     * @var int
     */
    public $statementnumber;

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
            if ($transaction->getDebitCredit() == DebitCredit::CREDIT()) {
                $this->closevalue = $this->closevalue->add($transaction->getValue());
            } else {
                $this->closevalue = $this->closevalue->subtract($transaction->getValue());
            }
        }
    }

    public function getStatementnumber(): int
    {
        return $this->statementnumber;
    }

    public function setStatementnumber(int $statementnumber): void
    {
        $this->statementnumber = $statementnumber;
    }
}