<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static InvoiceDebitCredit CREDIT()
 * @method static InvoiceDebitCredit DEBIT()
 * @method static InvoiceDebitCredit EMPTY()
 */
class InvoiceDebitCredit extends Enum
{
    public const CREDIT      = "C";
    public const DEBIT       = "D";
    public const EMPTY       = "";
}
