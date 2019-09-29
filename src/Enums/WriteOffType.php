<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @see MatchLine
 *
 * Add the type attribute in order to determine what to do with the match difference. Possible values are:
 *
 * @method static self CURRENCY()
 * @method static self WRITEOFF()
 * @method static self DISCOUNT()
 *
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
final class WriteOffType extends Enum
{
    private const CURRENCY = "currency";
    private const WRITEOFF = "writeoff";
    private const DISCOUNT = "discount";
}