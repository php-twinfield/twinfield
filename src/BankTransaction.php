<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\Transaction;
use PhpTwinfield\Transactions\TransactionFields\AutoBalanceVatField;
use PhpTwinfield\Transactions\TransactionFields\CodeNumberOfficeFields;
use PhpTwinfield\Transactions\TransactionFields\DestinyField;
use PhpTwinfield\Transactions\TransactionFields\FreeTextFields;
use PhpTwinfield\Transactions\TransactionFields\LinesField;
use PhpTwinfield\Transactions\TransactionFields\RaiseWarningField;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
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
    use CodeNumberOfficeFields;
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
        Assert::lessThan($this->getLineCount(), 500);

        /*
         * Calls the addLine() method on the LinesField trait. Uses an alias in the `use` statement at top of this
         * class, because parent::addLine() doesn't work for traits.
         */
        $this->traitAddLine($line);

        if (!$line->getLineType()->equals(LineType::TOTAL())) {
            /*
             * Don't add total lines to the closevalue, they are summaries of the details and vat lines.
             *
             * @link https://github.com/php-twinfield/twinfield/issues/39
             */
            if ($line->getDebitCredit()->equals(DebitCredit::CREDIT())) {
                $this->closevalue = $this->getClosevalue()->add($line->getValue());
            } else {
                $this->closevalue = $this->getClosevalue()->subtract($line->getValue());
            }
        }
    }
}
