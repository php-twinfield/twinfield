<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionLineFields\DateField;
use Webmozart\Assert\Assert;

/**
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements
 */
class ElectronicBankStatement
{
    use StartAndCloseValueFields;
    use DateField;
    use OfficeField;
    use StatementNumberField;

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

    public function __construct()
    {
        $this->currency   = new Currency("EUR");
        $this->startvalue = new Money(0, $this->getCurrency());
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount(string $account)
    {
        $this->account = $account;
        $this->iban = null;
        $this->code = null;
    }

    public function getIban()
    {
        return $this->iban;
    }

    public function setIban(string $iban)
    {
        $this->iban = $iban;
        $this->account = null;
        $this->code = null;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->account = null;
        $this->iban = null;
    }

    /**
     * @return bool
     */
    public function isImportDuplicate()
    {
        return $this->importDuplicate;
    }

    public function setImportDuplicate(bool $importDuplicate)
    {
        $this->importDuplicate = $importDuplicate;
    }

    /**
     * @return array|ElectronicBankStatementTransaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @param array|ElectronicBankStatementTransaction[] $transactions
     */
    public function setTransactions(array $transactions)
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

}