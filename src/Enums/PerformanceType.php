<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PerformanceType EMPTY()
 * @method static PerformanceType GOODS()
 * @method static PerformanceType SERVICES()
 */
class PerformanceType extends Enum
{
    public const EMPTY            = "";
    public const GOODS           = "goods";
    public const SERVICES        = "services";
}