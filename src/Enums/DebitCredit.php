<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static DebitCredit DEBIT()
 * @method static DebitCredit CREDIT()
 */
class DebitCredit extends Enum
{
    protected const DEBIT = "debit";
    protected const CREDIT = "credit";
}