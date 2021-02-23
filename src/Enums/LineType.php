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
    protected const TOTAL = "total";
    protected const DETAIL = "detail";
    protected const VAT = "vat";
}