<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static RemittanceAdviceSendType BYEMAIL()
 * @method static RemittanceAdviceSendType TOFILEMANAGER()
 */
class RemittanceAdviceSendType extends Enum
{
    protected const BYEMAIL             = 'ByEmail';
    protected const TOFILEMANAGER       = 'ToFileManager';
}