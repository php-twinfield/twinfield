<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Destiny TEMPORARY()
 * @method static Destiny FINAL()
 */
class Destiny extends Enum
{
    protected const TEMPORARY = 'temporary'; // Also called 'provisional'
    protected const FINAL     = 'final';
}