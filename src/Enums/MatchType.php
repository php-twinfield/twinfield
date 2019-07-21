<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MatchType CUSTOMERSUPPLIER()
 * @method static MatchType EMPTY()
 * @method static MatchType MATCHABLE()
 * @method static MatchType NOTMATCHABLE()
 */
class MatchType extends Enum
{
    public const CUSTOMERSUPPLIER       = "customersupplier";
    public const EMPTY                   = "";
    public const MATCHABLE              = "matchable";
    public const NOTMATCHABLE           = "notmatchable";

}
