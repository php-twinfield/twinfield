<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static FreeTextType AMOUNT()
 * @method static FreeTextType TEXT()
 */
class FreeTextType extends Enum
{
    protected const AMOUNT      = 'amount';
    protected const TEXT        = 'text';
}