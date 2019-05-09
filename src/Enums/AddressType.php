<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static AddressType CONTACT()
 * @method static AddressType INVOICE()
 * @method static AddressType POSTAL()
 */
class AddressType extends Enum
{
    protected const CONTACT     = 'contact';
    protected const INVOICE     = 'invoice';
    protected const POSTAL      = 'postal';
}