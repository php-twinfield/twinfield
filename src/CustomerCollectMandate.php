<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\Level2\Customer\CollectMandateIDField;
use PhpTwinfield\Fields\Level1234\Level2\Customer\FirstRunDateField;
use PhpTwinfield\Fields\Level1234\Level2\Customer\SignatureDateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerCollectMandate extends BaseObject
{
    use CollectMandateIDField;
    use FirstRunDateField;
    use SignatureDateField;

    public function __construct()
    {
        $this->ID = uniqid();
    }
}
