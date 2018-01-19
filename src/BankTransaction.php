<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\Transaction;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
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
class BankTransaction implements Transaction
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

    use LinesField {
        addLine as protected traitAddLine;
    }

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

    public function __construct()
    {
        $this->currency   = new Currency("EUR");
        $this->startvalue = new Money(0, $this->getCurrency());
    }

    public function getLineClassName(): string
    {
        return Transactions\BankTransactionLine\Base::class;
    }

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

    public function addLine(Transactions\BankTransactionLine\Base $line): void
    {
        Assert::notEmpty($this->startvalue);

        /*
         * Max is 500 lines. 
         */
        Assert::lessThanEq(count($this->getLines()), 500);

        /*
         * Calls the addLine() method on the LinesField trait. Uses an alias in the `use` statement at top of this
         * class, because parent::addLine() doesn't work for traits.
         */
        $this->traitAddLine($line);

        if (!$line->getType()->equals(LineType::TOTAL())) {
            /*
             * Don't add total lines to the closevalue, they are summaries of the details and vat lines.
             *
             * @link https://github.com/php-twinfield/twinfield/issues/39
             */
            if ($line->getDebitCredit()->equals(DebitCredit::CREDIT())) {
                $this->closevalue = $this->closevalue->add($line->getValue());
            } else {
                $this->closevalue = $this->closevalue->subtract($line->getValue());
            }
        }
    }

    /**
     * When creating a new bank transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a bank transaction, the related transaction number should be provided.
     *
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number)
    {
        $this->number = $number;
        return $this;
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
     * Transaction type code.
     *
     * @param string|int|null $code
     * @return $this
     */
    public function setCode($code)
    {
        Assert::nullOrScalar($code);

        $this->code = $code;
        return $this;
    }
}
