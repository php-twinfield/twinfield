<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static DebitCredit CREDIT()
 * @method static DebitCredit DEBIT()
 */
class DebitCredit extends Enum
{
    protected const CREDIT      = "credit";
    protected const DEBIT       = "debit";

    final public function invert(): self
    {
        if ($this->equals(self::DEBIT())) {
            return self::CREDIT();
        }
        return self::DEBIT();
    }
}