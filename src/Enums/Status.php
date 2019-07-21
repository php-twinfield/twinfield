<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Status ACTIVE()
 * @method static Status DELETED()
 * @method static Status EMPTY()
 * @method static Status HIDDEN()
 */
class Status extends Enum
{
    public const ACTIVE      = 'active';
    public const DELETED     = 'deleted';
    public const EMPTY       = '';
    public const HIDDEN      = 'hide';
}
