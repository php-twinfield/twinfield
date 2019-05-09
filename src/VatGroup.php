<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class VatGroup
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatGroup extends BaseObject
{
    use CodeField;
    use NameField;
    use ShortNameField;
}