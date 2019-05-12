<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static AddressType CONTACT()
 * @method static AddressType EMPTY()
 * @method static AddressType INVOICE()
 * @method static AddressType POSTAL()
 */
class AddressType extends Enum
{
    public const CONTACT     = 'contact';
    public const EMPTY       = '';
    public const INVOICE     = 'invoice';
    public const POSTAL      = 'postal';
}