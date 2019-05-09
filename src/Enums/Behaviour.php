<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Behaviour NORMAL()
 * @method static Behaviour SYSTEM()
 * @method static Behaviour TEMPLATE()
 */
class Behaviour extends Enum
{
    protected const NORMAL          = 'normal';
    protected const SYSTEM          = 'system';
    protected const TEMPLATE        = 'template';
}