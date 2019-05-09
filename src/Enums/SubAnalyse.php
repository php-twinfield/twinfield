<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static SubAnalyse FALSE()
 * @method static SubAnalyse MAYBE()
 * @method static SubAnalyse TRUE()
 */
class SubAnalyse extends Enum
{
    protected const FALSE       = 'false';
    protected const MAYBE       = 'maybe';
    protected const TRUE        = 'true';
}