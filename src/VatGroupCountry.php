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
class VatGroupCountry extends BaseObject implements HasCodeInterface
{
    use CodeField;
    use NameField;
    use ShortNameField;
    
    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }
}