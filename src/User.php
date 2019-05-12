<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\User\IsCurrentUserField;
use PhpTwinfield\Fields\User\LevelField;

class User extends BaseObject
{
    use CodeField;
    use CreatedField;
    use IsCurrentUserField;
    use LevelField;
    use ModifiedField;
    use NameField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
}
