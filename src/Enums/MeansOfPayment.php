<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MeansOfPayment CHEQUES()
 * @method static MeansOfPayment NONE()
 * @method static MeansOfPayment PAYMENTFILE()
 */
class MeansOfPayment extends Enum
{
    protected const CHEQUES         = 'cheques';
    protected const NONE            = 'none';
    protected const PAYMENTFILE     = 'paymentfile';
}