<?php
namespace PhpTwinfield\Mappers;

use Illuminate\Support\Collection;
use PhpTwinfield\BankStatement;
use stdClass;

class BankStatementMapper extends BaseMapper
{
    /**
     * @throws \PhpTwinfield\Exception
     */
    public static function map(stdClass $bankStatements): Collection
    {
        $statements = new Collection();

        foreach( $bankStatements as $bankStatement )
        {
            if( property_exists( $bankStatement, 'BankStatement' ) )
                $statements[] = new BankStatement( $bankStatement->BankStatement );
        }

        return $statements;
    }
}
