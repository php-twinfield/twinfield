<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static SubAnalyse EMPTY()
 * @method static SubAnalyse FALSE()
 * @method static SubAnalyse MAYBE()
 * @method static SubAnalyse TRUE()
 */
class SubAnalyse extends Enum
{
    public const EMPTY       = '';
    public const FALSE       = 'false';
    public const MAYBE       = 'maybe';
    public const TRUE        = 'true';
}
