<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;

class ElectronicBankStatement
{
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
     * Currency code. Set to the currency of the corresponding bank day book when left empty.
     *
     * @var Currency
     */
    private $currency;

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
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param array $transactions
     */
    public function setTransactions(array $transactions): void
    {
        $this->transactions = $transactions;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    protected function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getStatementnumber(): int
    {
        return $this->statementnumber;
    }

    public function setStatementnumber(int $statementnumber): void
    {
        $this->statementnumber = $statementnumber;
    }

    public function getOffice(): Office
    {
        return $this->office;
    }

    public function setOffice(Office $office): void
    {
        $this->office = $office;
    }

    public function getStartvalue(): Money
    {
        return $this->startvalue;
    }

    public function setStartvalue(Money $startvalue): void
    {
        $this->setCurrency($startvalue->getCurrency());
        $this->startvalue = $startvalue;
    }

    public function getClosevalue(): Money
    {
        return $this->closevalue;
    }

    public function setClosevalue(Money $closevalue): void
    {
        $this->setCurrency($closevalue->getCurrency());
        $this->closevalue = $closevalue;
    }
}