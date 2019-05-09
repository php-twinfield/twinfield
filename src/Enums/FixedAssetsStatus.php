<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static FixedAssetsStatus ACTIVE()
 * @method static FixedAssetsStatus INACTIVE()
 * @method static FixedAssetsStatus SOLD()
 * @method static FixedAssetsStatus TOBEACTIVATED()
 */
class FixedAssetsStatus extends Enum
{
    protected const ACTIVE              = "active";
    protected const INACTIVE            = "inactive";
    protected const SOLD                = "sold";
    protected const TOBEACTIVATED       = "tobeactivated";
}