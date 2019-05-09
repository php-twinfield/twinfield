<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\Rate\BeginDateField;
use PhpTwinfield\Fields\Rate\EndDateField;
use PhpTwinfield\Fields\Rate\ExternalRateField;
use PhpTwinfield\Fields\Rate\InternalRateField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates
 * @todo Add documentation and typehints to all properties.
 */
class RateRateChange extends BaseObject
{
    use BeginDateField;
    use EndDateField;
    use ExternalRateField;
    use IDField;
    use InternalRateField;
    use StatusField;

    public function __construct()
    {
        $this->ID = uniqid();
    }
}
