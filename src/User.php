<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\EmailField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\User\AcceptExtraCostField;
use PhpTwinfield\Fields\User\CultureField;
use PhpTwinfield\Fields\User\CultureNameField;
use PhpTwinfield\Fields\User\CultureNativeNameField;
use PhpTwinfield\Fields\User\DemoField;
use PhpTwinfield\Fields\User\DemoLockedField;
use PhpTwinfield\Fields\User\ExchangeQuotaField;
use PhpTwinfield\Fields\User\ExchangeQuotaLockedField;
use PhpTwinfield\Fields\User\FileManagerQuotaField;
use PhpTwinfield\Fields\User\FileManagerQuotaLockedField;
use PhpTwinfield\Fields\User\IsCurrentUserField;
use PhpTwinfield\Fields\User\LevelField;
use PhpTwinfield\Fields\User\PasswordField;
use PhpTwinfield\Fields\User\TypeField;
use PhpTwinfield\Fields\User\TypeLockedField;

class User extends BaseObject
{
    use AcceptExtraCostField;
    use CodeField;
    use CreatedField;
    use CultureField;
    use CultureNameField;
    use CultureNativeNameField;
    use DemoField;
    use DemoLockedField;
    use EmailField;
    use ExchangeQuotaField;
    use ExchangeQuotaLockedField;
    use FileManagerQuotaField;
    use FileManagerQuotaLockedField;
    use IsCurrentUserField;
    use LevelField;
    use ModifiedField;
    use NameField;
    use PasswordField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use TypeField;
    use TypeLockedField;
}
