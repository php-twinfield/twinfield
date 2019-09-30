<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class MatchCodes
 * @package PhpTwinfield
 *
 * The following matching types are available:
 *
 * @method static self SUSPENSE_ACCOUNTS() Matching suspense accounts.
 * @method static self CUSTOMERS() Matching customers.
 * @method static self SUPPLIERS() Matching suppliers.
 * @method static self PROJECTS() Matching projects.
 *
 * @link https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching
 */
final class MatchCode extends Enum
{
    private const SUSPENSE_ACCOUNTS = "070";
    private const CUSTOMERS = "170";
    private const SUPPLIERS = "270";
    private const PROJECTS = "370";
}