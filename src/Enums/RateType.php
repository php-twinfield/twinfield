<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RateType QUANTITY()
 * @method static RateType TIME()
 */
class RateType extends Enum
{
    public const QUANTITY       = "quantity";
    public const TIME           = "time";
}