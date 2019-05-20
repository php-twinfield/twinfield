<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Transaction\RegimeField;
use PhpTwinfield\JournalTransactionLine;

/*
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions
 */
class JournalTransaction extends BaseTransaction
{
    use RegimeField;

    /*
     * @return string
     */
    public function getLineClassName(): string
    {
        return JournalTransactionLine::class;
    }

    /*
     * @param JournalTransactionLine $line
     * @return $this
     */
    public function addLine(JournalTransactionLine $line)
    {
        parent::addLine($line);

        return $this;
    }
}
