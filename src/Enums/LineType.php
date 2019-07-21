<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static LineType DETAIL()
 * @method static LineType EMPTY()
 * @method static LineType TOTAL()
 * @method static LineType VAT()
 */
class LineType extends Enum
{
    public const DETAIL     = "detail";
    public const EMPTY      = "";
    public const TOTAL      = "total";
    public const VAT        = "vat";
}
