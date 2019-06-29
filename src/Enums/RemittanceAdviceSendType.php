<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RemittanceAdviceSendType BYEMAIL()
 * @method static RemittanceAdviceSendType EMPTY()
 * @method static RemittanceAdviceSendType TOFILEMANAGER()
 */
class RemittanceAdviceSendType extends Enum
{
    public const BYEMAIL             = 'ByEmail';
    public const EMPTY               = '';
    public const TOFILEMANAGER       = 'ToFileManager';
}
