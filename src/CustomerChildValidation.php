<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\TypeField;
use PhpTwinfield\Fields\ElementValueField;
use PhpTwinfield\Fields\LevelField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerChildValidation extends BaseObject
{
    use ElementValueField;
    use LevelField;
    use TypeField;
}
