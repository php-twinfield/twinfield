<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Order ASCENDING()
 * @method static Order DESCENDING()
 */
class Order extends Enum
{
    protected const ASCENDING = 'ascending';
    protected const DESCENDING = 'descending';
}