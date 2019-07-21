<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dim1Field;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\LineTypeField;
use PhpTwinfield\Fields\PercentageField;
use PhpTwinfield\Fields\VatCode\GroupCountryField;
use PhpTwinfield\Fields\VatCode\GroupField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodeAccount extends BaseObject
{
    use Dim1Field;
    use IDField;
    use GroupCountryField;
    use GroupField;
    use LineTypeField;
    use PercentageField;
}
