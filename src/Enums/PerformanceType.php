<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PerformanceType GOODS()
 * @method static PerformanceType NONE()
 * @method static PerformanceType SERVICES()
 */
class PerformanceType extends Enum
{
    protected const GOODS           = "goods";
    protected const NONE            = "";
    protected const SERVICES        = "services";
}