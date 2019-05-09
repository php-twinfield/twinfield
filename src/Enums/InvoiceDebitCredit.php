<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static InvoiceDebitCredit CREDIT()
 * @method static InvoiceDebitCredit DEBIT()
 */
class InvoiceDebitCredit extends Enum
{
    protected const CREDIT      = "C";
    protected const DEBIT       = "D";
}