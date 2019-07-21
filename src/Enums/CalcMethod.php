<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CalcMethod EMPTY()
 * @method static CalcMethod LINEAR()
 * @method static CalcMethod LINEARPERCENTAGE()
 * @method static CalcMethod NONE()
 * @method static CalcMethod REDUCEBALANCE()
 */
class CalcMethod extends Enum
{
    public const EMPTY                   = "";
    public const LINEAR                  = "linear";
    public const LINEARPERCENTAGE        = "linearpercentage";
    public const NONE                    = "none";
    public const REDUCEBALANCE           = "reducebalance";
}
