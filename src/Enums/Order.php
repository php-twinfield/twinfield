<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Order ASCENDING()
 * @method static Order DESCENDING()
 */
class Order extends Enum
{
    public const ASCENDING       = 'ascending';
    public const DESCENDING      = 'descending';
}