<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\CreatedField;
use PhpTwinfield\Fields\ModifiedField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\Office\CountryCodeField;
use PhpTwinfield\Fields\Office\VatFirstQuarterStartsInField;
use PhpTwinfield\Fields\Office\VatPeriodField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UserField;

class Office extends BaseObject
{
    use CodeField;
    use CountryCodeField;
    use CreatedField;
    use ModifiedField;
    use NameField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UserField;
    use VatFirstQuarterStartsInField;
    use VatPeriodField;

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }

    public function __toString()
    {
        return $this->getCode();
    }
}
