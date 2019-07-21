<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static FixedAssetsStatus ACTIVE()
 * @method static FixedAssetsStatus EMPTY()
 * @method static FixedAssetsStatus INACTIVE()
 * @method static FixedAssetsStatus SOLD()
 * @method static FixedAssetsStatus TOBEACTIVATED()
 */
class FixedAssetsStatus extends Enum
{
    public const ACTIVE              = "active";
    public const EMPTY                = "";
    public const INACTIVE            = "inactive";
    public const SOLD                = "sold";
    public const TOBEACTIVATED       = "tobeactivated";
}
