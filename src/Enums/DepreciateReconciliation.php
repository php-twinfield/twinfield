<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static DepreciateReconciliation EMPTY()
 * @method static DepreciateReconciliation FROMPURCHASEDATE()
 * @method static DepreciateReconciliation FROMRECONCILIATION()
 */
class DepreciateReconciliation extends Enum
{
    public const EMPTY                    = "";
    public const FROMPURCHASEDATE        = "from_purchase_date";
    public const FROMRECONCILIATION      = "from_reconciliation";
}