<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static InvoiceStatus CONCEPT()
 * @method static InvoiceStatus DEFAULT()
 * @method static InvoiceStatus FINAL()
 */
class InvoiceStatus extends Enum
{
    protected const CONCEPT     = "concept"; // Also called 'provisional'
    protected const DEFAULT     = "default";
    protected const FINAL       = "final";
}