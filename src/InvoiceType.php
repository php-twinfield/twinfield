<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class InvoiceType
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class InvoiceType extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use ShortNameField;
}