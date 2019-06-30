<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static UserType ACCOUNTANT()
 * @method static UserType ACCOUNTANTCUSTOMER()
 * @method static UserType EMPTY()
 * @method static UserType REGULAR()
 */
class UserType extends Enum
{
    public const ACCOUNTANT             = 'accountant';
    public const ACCOUNTANTCUSTOMER     = 'accountantcustomer';
    public const EMPTY                  = '';
    public const REGULAR                = 'regular';
}
