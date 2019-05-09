<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MatchType CUSTOMERSUPPLIER()
 * @method static MatchType NOTMATCHABLE()
 * @method static MatchType MATCHABLE()
 */
class MatchType extends Enum
{
    public const CUSTOMERSUPPLIER       = "customersupplier";
    public const NOTMATCHABLE           = "notmatchable";
    public const MATCHABLE              = "matchable";
}