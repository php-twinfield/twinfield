<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentMethod BANK()
 * @method static PaymentMethod CASH()
 * @method static PaymentMethod CASHONDELIVERY()
 * @method static PaymentMethod CHEQUE()
 * @method static PaymentMethod DA()
 */
class PaymentMethod extends Enum
{
    protected const BANK                = 'bank';
    protected const CASH                = 'cash';
    protected const CASHONDELIVERY      = 'cashondelivery';
    protected const CHEQUE              = 'cheque';
    protected const DA                  = 'da';
}