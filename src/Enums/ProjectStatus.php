<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static ProjectStatus ACTIVE()
 * @method static ProjectStatus DELETED()
 * @method static ProjectStatus HIDE()
 */
class ProjectStatus extends Enum
{
    protected const ACTIVE = "active";
    protected const DELETED = "deleted";
    protected const HIDE = "hide";
}
