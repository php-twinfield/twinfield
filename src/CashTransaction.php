<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionLine;
use Webmozart\Assert\Assert;

/**
 * NOTE:    Can not use the trait "PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields" because the
 *          method "getCurrency" is not compatible with the method "getCurrency" of ""PhpTwinfield\BaseTransaction"".
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions
 */
class CashTransaction extends BaseTransaction
{
    use StatementNumberField;

    /**
     * Opening balance. If not provided, the opening balance is set to zero.
     *
     * @var Money
     */
    private $startvalue;

    /**
     * Closing balance. If not provided, the closing balance is set to zero.
     *
     * @var Money
     */
    private $closevalue;

    public function __construct()
    {
        $this->startvalue = new Money(0, new Currency('EUR'));
    }

    /**
     * @return string
     */
    public function getLineClassName(): string
    {
        return CashTransactionLine::class;
    }

    /**
     * When creating a new cash transaction, don't include this tag as the transaction number is determined by the
     * system. When updating a cash transaction, the related transaction number should be provided.
     *
     * @param int|null $number
     * @return $this
     */
    public function setNumber(?int $number): BaseTransaction
    {
        return parent::setNumber($number);
    }

    /**
     * Set the currency. Can only be done when the start value is still 0.
     *
     * @param string|null $currency
     * @return $this
     */
    public function setCurrency(?string $currency): BaseTransaction
    {
        Assert::true($this->startvalue->isZero());
        $this->setStartvalue(new Money(0, new Currency($currency)));

        return $this;
    }

    /**
     * @return Money
     */
    public function getStartvalue(): Money
    {
        return $this->startvalue;
    }

    /**
     * @param Money $startvalue
     * @return $this
     */
    public function setStartvalue(Money $startvalue): self
    {
        parent::setCurrency($startvalue->getCurrency());
        $this->startvalue = $startvalue;
        $this->closevalue = $startvalue;

        return $this;
    }

    /**
     * @return Money
     */
    public function getClosevalue(): Money
    {
        return $this->closevalue ?? new Money(0, new Currency($this->getCurrency()));
    }

    /**
     * @param TransactionLine $line
     * @return $this
     */
    public function addLine(TransactionLine $line)
    {
        parent::addLine($line);

        /** @var CashTransactionLine $line */
        if (!$line->getLineType()->equals(LineType::TOTAL())) {
            /*
             * Don't add total lines to the closevalue, they are summaries of the details and vat lines.
             */
            if ($line->getDebitCredit()->equals(DebitCredit::CREDIT())) {
                $this->closevalue = $this->getClosevalue()->add($line->getValue());
            } else {
                $this->closevalue = $this->getClosevalue()->subtract($line->getValue());
            }
        }

        return $this;
    }
}
