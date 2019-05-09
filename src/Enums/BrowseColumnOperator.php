<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static BrowseColumnOperator BETWEEN()
 * @method static BrowseColumnOperator EQUAL()
 * @method static BrowseColumnOperator NONE()
 */
class BrowseColumnOperator extends Enum
{
    protected const BETWEEN = 'between';
    protected const EQUAL = 'equal';
    protected const NONE = 'none';
}