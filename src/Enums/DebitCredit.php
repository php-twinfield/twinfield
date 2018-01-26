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

    final public function invert(): self
    {
        if ($this->equals(self::DEBIT())) {
            return self::CREDIT();
        }
        return self::DEBIT();
    }
}