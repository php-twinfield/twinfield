<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static DepreciateReconciliation FROMPURCHASEDATE()
 * @method static DepreciateReconciliation FROMRECONCILIATION()
 */
class DepreciateReconciliation extends Enum
{
    protected const FROMPURCHASEDATE        = "from_purchase_date";
    protected const FROMRECONCILIATION      = "from_reconciliation";
}