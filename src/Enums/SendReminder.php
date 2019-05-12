<?php

namespace PhpTwinfield\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static SendReminder EMAIL()
 * @method static SendReminder EMPTY()
 * @method static SendReminder FALSE()
 * @method static SendReminder TRUE()
 */
class SendReminder extends Enum
{
    public const EMAIL      = "email";
    public const EMPTY      = "";
    public const FALSE      = "false";
    public const TRUE       = "true";
}