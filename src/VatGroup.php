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
class VatGroup extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use ShortNameField;
}