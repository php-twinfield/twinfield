<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\Dim1Field;
use PhpTwinfield\Fields\PercentageField;
use PhpTwinfield\Fields\VatCode\LineTypeField;
use PhpTwinfield\Fields\VatCode\VatGroupCountryField;
use PhpTwinfield\Fields\VatCode\VatGroupField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT
 * @todo Add documentation and typehints to all properties.
 */
class VatCodeAccount extends BaseObject
{
    use IDField;
    use Dim1Field;
    use LineTypeField;
    use PercentageField;
    use VatGroupCountryField;
    use VatGroupField;
}
