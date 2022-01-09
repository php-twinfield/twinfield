<?php

namespace PhpTwinfield\ApiConnectors;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpTwinfield\BankStatement;
use PhpTwinfield\Mappers\BankStatementMapper;
use PhpTwinfield\Request\Query\Bank;

class BankStatementApiConnector extends BaseApiConnector
{
    /**
     * Requests a bankstatement based off the passed in from and to date.
     *
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return Collection The requested bank statements
     * @throws Exception
     */
    public function get(Carbon $dateFrom, Carbon $dateTo): Collection
    {
        // Make a request to query bank statement(s). Set the required values
        $request_bank = new Bank();
        $request_bank
            ->setFromDate($dateFrom)
            ->setToDate($dateTo);

        $bankStatements = $this->getQueryService()->sendQuery( $request_bank );

        dd( $bankStatements );

        return BankStatementMapper::map( $bankStatements );
    }
}
