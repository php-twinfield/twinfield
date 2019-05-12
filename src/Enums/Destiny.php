<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Destiny EMPTY()
 * @method static Destiny TEMPORARY()
 * @method static Destiny FINAL()
 */
class Destiny extends Enum
{
    public const EMPTY           = '';
    public const FINAL           = 'final';
    public const TEMPORARY       = 'temporary'; // Also called 'provisional'
}