<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static ChildValidationType CODE()
 * @method static ChildValidationType EMPTY()
 * @method static ChildValidationType GROUP()
 * @method static ChildValidationType TYPE()
 */
class ChildValidationType extends Enum
{
    public const CODE        = "code";
    public const EMPTY       = "";
    public const GROUP       = "group";
    public const TYPE        = "type";
}
