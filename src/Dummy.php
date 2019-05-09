<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class Dummy
 * Dummy object for usage in fields that have multiple possibilities for the real object
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Dummy extends BaseObject
{
    use CodeField;
    use NameField;
    use ShortNameField;
}