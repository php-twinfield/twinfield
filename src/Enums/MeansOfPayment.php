<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MeansOfPayment CHEQUES()
 * @method static MeansOfPayment EMPTY()
 * @method static MeansOfPayment NONE()
 * @method static MeansOfPayment PAYMENTFILE()
 */
class MeansOfPayment extends Enum
{
    public const CHEQUES         = 'cheques';
    public const EMPTY           = '';
    public const NONE            = 'none';
    public const PAYMENTFILE     = 'paymentfile';
}
