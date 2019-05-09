<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static AccountType BALANCE()
 * @method static AccountType INHERIT()
 * @method static AccountType PROFITANDLOSS()
 */
class AccountType extends Enum
{
    protected const BALANCE             = 'balance';
    protected const INHERIT             = 'inherit';
    protected const PROFITANDLOSS       = 'profitandloss';
}