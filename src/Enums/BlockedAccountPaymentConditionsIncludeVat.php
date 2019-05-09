<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BlockedAccountPaymentConditionsIncludeVat FALSE()
 * @method static BlockedAccountPaymentConditionsIncludeVat NONE()
 * @method static BlockedAccountPaymentConditionsIncludeVat TRUE()
 */
class BlockedAccountPaymentConditionsIncludeVat extends Enum
{
    protected const FALSE  = 'false';
    protected const NONE    = '';
    protected const TRUE    = 'true';
}