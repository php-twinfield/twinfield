<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static VatType NONE()
 * @method static VatType PURCHASE()
 * @method static VatType SALES()
 */
class VatType extends Enum
{
    public const NONE           = "";
    public const PURCHASE       = "purchase";
    public const SALES          = "sales";
}