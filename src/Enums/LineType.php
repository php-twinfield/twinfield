<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static LineType TOTAL()
 * @method static LineType DETAIL()
 * @method static LineType VAT()
 */
class LineType extends Enum
{
    public const TOTAL = "total";
    public const DETAIL = "detail";
    public const VAT = "vat";
}