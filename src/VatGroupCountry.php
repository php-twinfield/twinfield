<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;

/**
 * Class VatGroupCountry
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatGroupCountry
{
    use CodeField;
    use NameField;
    use ShortNameField;
}