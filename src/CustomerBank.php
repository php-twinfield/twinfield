<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\Level1234\Level2\AccountNumberField;
use PhpTwinfield\Fields\Level1234\Level2\AddressField2Field;
use PhpTwinfield\Fields\Level1234\Level2\AddressField3Field;
use PhpTwinfield\Fields\Level1234\Level2\AscriptionField;
use PhpTwinfield\Fields\Level1234\Level2\BankBlockedField;
use PhpTwinfield\Fields\Level1234\Level2\BankNameField;
use PhpTwinfield\Fields\Level1234\Level2\BicCodeField;
use PhpTwinfield\Fields\Level1234\Level2\CityField;
use PhpTwinfield\Fields\Level1234\Level2\CountryField;
use PhpTwinfield\Fields\Level1234\Level2\DefaultField;
use PhpTwinfield\Fields\Level1234\Level2\IbanField;
use PhpTwinfield\Fields\Level1234\Level2\NatBicCodeField;
use PhpTwinfield\Fields\Level1234\Level2\PostcodeField;
use PhpTwinfield\Fields\Level1234\Level2\StateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerBank extends BaseObject
{
    use AccountNumberField;
    use AddressField2Field;
    use AddressField3Field;
    use AscriptionField;
    use BankNameField;
    use BankBlockedField;
    use BicCodeField;
    use CityField;
    use CountryField;
    use DefaultField;
    use IbanField;
    use IDField;
    use NatBicCodeField;
    use PostcodeField;
    use StateField;

    public function __construct()
    {
        $this->ID = (int)(date('Ymd'). sprintf("%03d", mt_rand(1, 999)));
    }
}
