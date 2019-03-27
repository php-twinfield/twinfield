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
    const TOTAL = "total";
    const DETAIL = "detail";
    const VAT = "vat";
}