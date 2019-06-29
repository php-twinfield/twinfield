<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CollectionSchema B2B()
 * @method static CollectionSchema CORE()
 * @method static CollectionSchema EMPTY()
 */
class CollectionSchema extends Enum
{
    public const B2B         = "b2b";
    public const CORE        = "core";
    public const EMPTY       = "";
}
