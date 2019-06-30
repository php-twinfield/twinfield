<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static InvoiceStatus CONCEPT()
 * @method static InvoiceStatus DEFAULT()
 * @method static InvoiceStatus EMPTY()
 * @method static InvoiceStatus FINAL()
 */
class InvoiceStatus extends Enum
{
    public const CONCEPT     = "concept"; // Also called 'provisional'
    public const DEFAULT     = "default";
    public const EMPTY        = "";
    public const FINAL       = "final";
}
