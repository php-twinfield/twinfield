<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class MatchsCodes
 * @package PhpTwinfield
 *
 * The following matching types are available:
 *
 * @method static self CUSTOMERS() Matching customers.
 * @method static self PROJECTS() Matching projects.
 * @method static self SUPPLIERS() Matching suppliers.
 * @method static self SUSPENSE_ACCOUNTS() Matching suspense accounts.
 *
 * @link https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
final class MatchCode extends Enum
{
    private const CUSTOMERS             = "170";
    private const PROJECTS              = "370";
    private const SUPPLIERS             = "270";
    private const SUSPENSE_ACCOUNTS     = "070";
}
