<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\Level2\CityField;
use PhpTwinfield\Fields\Dimensions\Level2\CountryField;
use PhpTwinfield\Fields\Dimensions\Level2\DefaultField;
use PhpTwinfield\Fields\Dimensions\Level2\EmailField;
use PhpTwinfield\Fields\Dimensions\Level2\Field1Field;
use PhpTwinfield\Fields\Dimensions\Level2\Field2Field;
use PhpTwinfield\Fields\Dimensions\Level2\Field3Field;
use PhpTwinfield\Fields\Dimensions\Level2\Field4Field;
use PhpTwinfield\Fields\Dimensions\Level2\Field5Field;
use PhpTwinfield\Fields\Dimensions\Level2\Field6Field;
use PhpTwinfield\Fields\Dimensions\Level2\PostcodeField;
use PhpTwinfield\Fields\Dimensions\Level2\TelefaxField;
use PhpTwinfield\Fields\Dimensions\Level2\TelephoneField;
use PhpTwinfield\Fields\Dimensions\Level2\TypeField;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\NameField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers
 * @todo Add documentation and typehints to all properties.
 */
class SupplierAddress extends BaseObject
{
    use CityField;
    use CountryField;
    use DefaultField;
    use EmailField;
    use Field1Field;
    use Field2Field;
    use Field3Field;
    use Field4Field;
    use Field5Field;
    use Field6Field;
    use IDField;
    use NameField;
    use PostcodeField;
    use TelefaxField;
    use TelephoneField;
    use TypeField;
}
