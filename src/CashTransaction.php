<?php

namespace PhpTwinfield;

use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Fields\Transaction\CloseValueField;
use PhpTwinfield\Fields\Transaction\StartValueField;
use PhpTwinfield\Fields\Transaction\StatementNumberField;

/*
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions
 */
class CashTransaction extends BaseTransaction
{
    use CloseValueField;
    use StartValueField;
    use StatementNumberField;

    public function __construct()
    {
        $this->closeValue = new \Money\Money(0, new \Money\Currency('EUR'));
        $this->startValue = new \Money\Money(0, new \Money\Currency('EUR'));
    }

    /*
     * @return string
     */
    public function getLineClassName(): string
    {
        return CashTransactionLine::class;
    }

    /*
     * @param CashTransactionLine $line
     * @return $this
     */
    public function addLine(CashTransactionLine $line)
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
