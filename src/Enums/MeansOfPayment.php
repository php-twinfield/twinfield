<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static MeansOfPayment NONE()
 * @method static MeansOfPayment PAYMENTFILE()
 */
class MeansOfPayment extends Enum
{
    protected const NONE = "none";
    protected const PAYMENTFILE = "paymentfile";
}
