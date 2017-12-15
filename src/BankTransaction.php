<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Transactions\TransactionFields\RaiseWarningField;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionLineFields\DateField;
use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;
use Webmozart\Assert\Assert;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions
 */
class BankTransaction
{
    use DestinyField;
    use AutoBalanceVatField;
    use PeriodField;
    use OfficeField;
    use DateField;
    use StatementNumberField;
    use RaiseWarningField;

    use StartAndCloseValueFields;

    use FreeTextFields;

    /**
     * The date/time on which the transaction was created.
     * Read-only attribute.
     *
     * @var \DateTimeImmutable
     */
    private $inputDate;

    /**
     * Transaction type code.
     *
     * @var mixed
     */
    private $code;

    /**
     * Transaction number.
     * When creating a new bank transaction, don't include this tag as the transaction number is determined by the system. When updating a bank transaction, the related transaction number should be provided.
     *
     * @var int
     */
    private $number;

    /**
     * Number of the bank statement. Don't confuse this number with the transaction number.
     *
     * @var int
     */
    private $statementnumber;

    /**
     * The bank transaction origin.
     * Read-only attribute.
     *
     * @var mixed
     */
    private $origin;

    /**
     * The date/time on which the bank transaction was modified the last time.
     * Read-only attribute.
     *
     * @var \DateTimeInterface
     */
    private $modificationDate;

    /**
     * @var Transactions\BankTransactionLine\Base[]
     */
    private $transactions = [];

    /**
     * The bank transaction origin.
     * Read-only attribute.
     *
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    public function getInputDate(): \DateTimeInterface
    {
        return $this->inputDate;
    }

    /**
     * @return Transactions\BankTransactionLine\Base[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transactions\BankTransactionLine\Base[] $transactions
     */
    public function setTransactions(array $transactions): void
    {
        Assert::allIsInstanceOf($transactions, Transactions\BankTransactionLine\Base::class);
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

    /**
     * When creating a new bank transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a bank transaction, the related transaction number should be provided.
     *
     * @return int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @return string|int|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string|int|null $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
