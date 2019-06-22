<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\LevelField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class UserRole
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class UserRole extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use LevelField;
    use NameField;
    use ShortNameField;
}