<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentMethod BANK()
 * @method static PaymentMethod CASH()
 * @method static PaymentMethod CASHONDELIVERY()
 * @method static PaymentMethod CHEQUE()
 * @method static PaymentMethod DA()
 * @method static PaymentMethod EMPTY()
 */
class PaymentMethod extends Enum
{
    public const BANK                = 'bank';
    public const CASH                = 'cash';
    public const CASHONDELIVERY      = 'cashondelivery';
    public const CHEQUE              = 'cheque';
    public const DA                  = 'da';
    public const EMPTY                = '';
}
