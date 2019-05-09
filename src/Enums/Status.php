<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Status ACTIVE()
 * @method static Status DELETED()
 * @method static Status HIDDEN()
 */
class Status extends Enum
{
    protected const ACTIVE      = 'active';
    protected const DELETED     = 'deleted';
    protected const HIDDEN      = 'hide';
}