<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\Level2\Customer\FirstRunDateField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\IDField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\SignatureDateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerCollectMandate extends BaseObject
{
    use FirstRunDateField;
    use IDField;
    use SignatureDateField;
}
