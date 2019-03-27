<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PerformanceType SERVICES()
 * @method static PerformanceType GOODS()
 */
class PerformanceType extends Enum
{
    const SERVICES = "services";
    const GOODS = "goods";
}