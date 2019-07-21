<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static VatType EMPTY()
 * @method static VatType PURCHASE()
 * @method static VatType SALES()
 */
class VatType extends Enum
{
    public const EMPTY          = "";
    public const PURCHASE       = "purchase";
    public const SALES          = "sales";
}
