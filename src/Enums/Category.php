<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Category SALES()
 * @method static Category PURCHASE()
 * @method static Category JOURNAL()
 */
class Category extends Enum
{
    protected const SALES = 'sales';
    protected const PURCHASE = 'purchase';
    protected const JOURNAL = 'journal';
}
