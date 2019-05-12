<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Behaviour EMPTY()
 * @method static Behaviour NORMAL()
 * @method static Behaviour SYSTEM()
 * @method static Behaviour TEMPLATE()
 */
class Behaviour extends Enum
{
    public const EMPTY           = '';
    public const NORMAL          = 'normal';
    public const SYSTEM          = 'system';
    public const TEMPLATE        = 'template';
}