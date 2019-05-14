<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class Country
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class Country
{
    use CodeField;
    use NameField;
    use ShortNameField;
}