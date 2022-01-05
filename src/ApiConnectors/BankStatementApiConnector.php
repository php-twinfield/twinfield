<?php

namespace PhpTwinfield\ApiConnectors;

use Carbon\Carbon;
use PhpTwinfield\Request\Query\Bank;

class BankStatementApiConnector extends BaseApiConnector
{
    /**
     * Requests a bankstatement based off the passed in from and to date.
     *
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Bank The requested bank statements
     * @throws Exception
     */
    public function get(Carbon $dateFrom, Carbon $dateTo)
    {
        // Make a request to query bank statement(s). Set the required values
        $request_bank = new Bank();
        $request_bank
            ->setFromDate($dateFrom)
            ->setToDate($dateTo);

        return $this->getQueryService()->sendQuery( $request_bank );
    }
}
