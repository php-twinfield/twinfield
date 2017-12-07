<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PerformanceType SERVICES()
 * @method static PerformanceType GOODS()
 */
class PerformanceType extends Enum
{
    protected const SERVICES = "services";
    protected const GOODS = "goods";
}