<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @see MatchLine
 *
 * Add the type attribute in order to determine what to do with the match difference. Possible values are:
 *
 * @method static self CURRENCY()
 * @method static self DISCOUNT()
 * @method static self EMPTY()
 * @method static self WRITEOFF()
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
final class WriteOffType extends Enum
{
    private const CURRENCY      = "currency";
    private const DISCOUNT      = "discount";
    private const EMPTY         = "";
    private const WRITEOFF      = "writeoff";
}
