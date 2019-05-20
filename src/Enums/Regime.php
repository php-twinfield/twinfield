<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Regime COMMERCIAL()
 * @method static Regime ECONOMIC()
 * @method static Regime EMPTY()
 * @method static Regime FISCAL()
 * @method static Regime GENERIC()
 */
class Regime extends Enum
{
    public const COMMERCIAL     = 'Commercial';
    public const ECONOMIC       = 'Economic';
    public const EMPTY          = '';
    public const FISCAL         = 'Fiscal';
    public const GENERIC        = 'Generic';
}