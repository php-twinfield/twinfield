<?php
namespace PhpTwinfield\Mappers;

use Illuminate\Support\Collection;
use PhpTwinfield\BankStatement;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Enums\PerformanceType;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Base;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;
use PhpTwinfield\Transactions\BankTransactionLine\Vat;
use PhpTwinfield\Util;

class BankStatementMapper extends BaseMapper
{
    /**
     * @throws \PhpTwinfield\Exception
     */
    public static function map(\stdClass $bankStatements): Collection
    {
        $statements = new Collection();

        foreach( $bankStatements as $bankStatement )
        {
            $statements[] = new BankStatement( $bankStatement->BankStatement );
        }

        return $statements;
    }
}
