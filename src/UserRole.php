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
class UserRole
{
    use CodeField;
    use NameField;
    use ShortNameField;
}