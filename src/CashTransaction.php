<?php

namespace PhpTwinfield;

use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Fields\Transaction\CloseAndStartValueFields;
use PhpTwinfield\Fields\Transaction\StatementNumberField;

/*
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions
 */
class CashTransaction extends BaseTransaction
{
    use CloseAndStartValueFields {
        setCurrency as protected traitSetCurrency;
    }

    use StatementNumberField;

    /*
     * @return string
     */
    public function getLineClassName(): string
    {
        return CashTransactionLine::class;
    }

    /*
     * Set the currency. Can only be done when the start value is still 0.
     *
     * @param Currency $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): parent
    {
        $this->traitSetCurrency($currency);

        return $this;
    }

    /*
     * @param $line
     * @return $this
     */
    public function addLine($line)
    {
        parent::addLine($line);

        /* @var CashTransactionLine $line */
        if (!$line->getLineType()->equals(LineType::TOTAL())) {
            /*
             * Don't add total lines to the close value, they are summaries of the details and vat lines.
             */
            if ($line->getDebitCredit()->equals(DebitCredit::CREDIT())) {
                $this->closeValue = $this->getCloseValue()->add($line->getValue());
            } else {
                $this->closeValue = $this->getCloseValue()->subtract($line->getValue());
            }
        }

        return $this;
    }
}
