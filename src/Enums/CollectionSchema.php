<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CollectionSchema CORE()
 * @method static CollectionSchema B2B()
 */
class CollectionSchema extends Enum
{
    protected const CORE = "core";
    protected const B2B = "b2b";
}
