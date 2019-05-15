<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Culture CESTINA()
 * @method static Culture DEUTSCH()
 * @method static Culture DANSK()
 * @method static Culture EMPTY()
 * @method static Culture ENGLISH()
 * @method static Culture FRANCAIS()
 * @method static Culture MAGYAR()
 * @method static Culture NEDERLANDS()
 */
class Culture extends Enum
{
    public const CESTINA        = "cs-CZ";
    public const DEUTSCH        = "de-DE";
    public const DANSK          = "da-DK";
    public const EMPTY          = "";
    public const ENGLISH        = "en-GB";
    public const FRANCAIS       = "fr-FR";
    public const MAGYAR         = "hu-HU";
    public const NEDERLANDS     = "nl-NL";
}