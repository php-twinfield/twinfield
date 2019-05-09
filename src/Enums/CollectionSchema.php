<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CollectionSchema B2B()
 * @method static CollectionSchema CORE()
 */
class CollectionSchema extends Enum
{
    protected const B2B         = "b2b";
    protected const CORE        = "core";
}