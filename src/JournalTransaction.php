<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Transaction\RegimeField;

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
}
