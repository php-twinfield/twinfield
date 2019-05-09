<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static ArticleType DISCOUNT()
 * @method static ArticleType NORMAL()
 * @method static ArticleType PREMIUM()
 */
class ArticleType extends Enum
{
    public const DISCOUNT       = "discount";
    public const NORMAL         = "normal";
    public const PREMIUM        = "premium";
}