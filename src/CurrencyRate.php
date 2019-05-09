<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Currency\CurrencyRateRateField;
use PhpTwinfield\Fields\Currency\StartDateField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
 * @todo Add documentation and typehints to all properties.
 */
class CurrencyRate extends BaseObject
{
    use CurrencyRateRateField;
    use StatusField;
    use StartDateField;
}
