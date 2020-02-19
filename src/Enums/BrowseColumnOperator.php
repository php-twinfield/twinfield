<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BrowseColumnOperator NONE()
 * @method static BrowseColumnOperator EQUAL()
 * @method static BrowseColumnOperator BETWEEN()
 * @method static BrowseColumnOperator LIKE()
 */
class BrowseColumnOperator extends Enum
{
    protected const NONE = 'none';
    protected const EQUAL = 'equal';
    protected const BETWEEN = 'between';
    protected const LIKE = 'like';
}
