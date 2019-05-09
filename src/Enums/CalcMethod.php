<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CalcMethod LINEAR()
 * @method static CalcMethod LINEARPERCENTAGE()
 * @method static CalcMethod NONE()
 * @method static CalcMethod REDUCEBALANCE()
 */
class CalcMethod extends Enum
{
    protected const LINEAR                  = "linear";
    protected const LINEARPERCENTAGE        = "linearpercentage";
    protected const NONE                    = "none";
    protected const REDUCEBALANCE           = "reducebalance";
}