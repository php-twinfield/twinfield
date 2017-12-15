<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Transactions\BankTransactionLine\Base;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionLineFields\PeriodField;
use Webmozart\Assert\Assert;

class BankTransaction
{
    use DestinyField;
    use AutoBalanceVatField;
    use PeriodField;
    use OfficeField;

    use StartAndCloseValueFields;

    use FreeTextFields;

    /**
     * The date/time on which the transaction was created.
     * Read-only attribute.
     *
     * @var \DateTimeImmutable
     */
    public $inputDate;

    /**
     * Transaction type code.
     *
     * @var mixed
     */
    public $code;

    /**
     * Transaction number.
     * When creating a new bank transaction, don't include this tag as the transaction number is determined by the system. When updating a bank transaction, the related transaction number should be provided.
     *
     * @var int
     */
    public $number;

    /**
     * Transaction date.
     * Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false. This overwrites the value of the raisewarning attribute as set on the root element.
     *
     * @var \DateTimeInterface
     */
    public $date;

    /**
     * Number of the bank statement. Don't confuse this number with the transaction number.
     *
     * @var int
     */
    public $statementnumber;

    /**
     * The bank transaction origin.
     * Read-only attribute.
     *
     * @var mixed
     */
    public $origin;

    /**
     * The date/time on which the bank transaction was modified the last time.
     * Read-only attribute.
     *
     * @var \DateTimeInterface
     */
    public $modificationDate;

    /**
     * @var Transactions\BankTransactionLine\Base[]
     */
    public $transactions = [];

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
}
