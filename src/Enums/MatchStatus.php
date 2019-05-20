<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MatchStatus AVAILABLE()
 * @method static MatchStatus EMPTY()
 * @method static MatchStatus MATCHABLE()
 * @method static MatchStatus MATCHED()
 * @method static MatchStatus NOTMATCHABLE()
 * @method static MatchStatus PROPOSED()
 */
class MatchStatus extends Enum
{
    public const AVAILABLE          = "available";
    public const EMPTY              = "";
    public const MATCHABLE          = "matchable";
    public const MATCHED          = "matched";
    public const NOTMATCHABLE       = "notmatchable";
    public const PROPOSED           = "proposed";
}