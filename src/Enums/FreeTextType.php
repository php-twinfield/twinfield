<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static FreeTextType AMOUNT()
 * @method static FreeTextType EMPTY()
 * @method static FreeTextType TEXT()
 */
class FreeTextType extends Enum
{
    public const AMOUNT      = 'amount';
    public const EMPTY        = '';
    public const TEXT        = 'text';
}