<?php

namespace PhpTwinfield;

use Money\Currency;
use Money\Money;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Transactions\TransactionFields\StartAndCloseValueFields;
use PhpTwinfield\Transactions\TransactionFields\StatementNumberField;
use PhpTwinfield\Transactions\TransactionLine;

/**
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions
 */
class CashTransaction extends BaseTransaction
{
    use StatementNumberField;
    use StartAndCloseValueFields {
        setCurrency as protected traitSetCurrency;
    }

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
     * Set the currency. Can only be done when the start value is still 0.
     *
     * @param Currency $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): BaseTransaction
    {
        $this->traitSetCurrency($currency);
        return $this;
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
