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
class CashBankBook extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use ShortNameField;
}