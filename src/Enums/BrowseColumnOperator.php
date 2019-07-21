<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BrowseColumnOperator BETWEEN()
 * @method static BrowseColumnOperator EMPTY()
 * @method static BrowseColumnOperator EQUAL()
 * @method static BrowseColumnOperator NONE()
 */
class BrowseColumnOperator extends Enum
{
    public const BETWEEN     = 'between';
    public const EMPTY       = '';
    public const EQUAL       = 'equal';
    public const NONE        = 'none';
}
