<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Destiny TEMPORARY()
 * @method static Destiny FINAL()
 */
class Destiny extends Enum
{
    const TEMPORARY = 'temporary'; // Also called 'provisional'
    const _FINAL    = 'final';
}