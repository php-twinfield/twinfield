<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static AccountType BALANCE()
 * @method static AccountType EMPTY()
 * @method static AccountType INHERIT()
 * @method static AccountType PROFITANDLOSS()
 */
class AccountType extends Enum
{
    public const BALANCE             = 'balance';
    public const EMPTY                = '';
    public const INHERIT             = 'inherit';
    public const PROFITANDLOSS       = 'profitandloss';
}
