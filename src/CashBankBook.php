<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class CashBankBook
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class CashBankBook extends BaseObject
{
    use CodeField;
    use NameField;
    use ShortNameField;
}