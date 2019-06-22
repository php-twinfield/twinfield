<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class PayCode
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class PayCode extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use ShortNameField;
}