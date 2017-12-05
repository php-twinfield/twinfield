<?php

namespace PhpTwinfield\Transactions;

use PhpTwinfield\Transactions\BankTransactionLine\Base;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use Webmozart\Assert\Assert;

class BankTransaction
{
    use DestinyField;
    use AutoBalanceVatField;
    use OfficeField;

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
     * Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
     *
     * @var string
     */
    private $period;

    /**
     * Transaction date.
     * Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false. This overwrites the value of the raisewarning attribute as set on the root element.
     *
     * @var \DateTimeInterface
     */
    private $date;

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
     * @var BankTransactionLine\Base[]
     */
    private $transactions;

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
     * @return BankTransactionLine\Base[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param BankTransactionLine\Base[] $transactions
     */
    public function setTransactions(array $transactions): void
    {
        Assert::allIsInstanceOf($transactions, BankTransactionLine\Base::class);
        Assert::notEmpty($this->startvalue);

        $this->transactions = $transactions;

        $this->closevalue = $this->startvalue;

        foreach ($transactions as $transaction) {
            $this->closevalue = $this->closevalue->add($transaction->getValue());
        }
    }
}
