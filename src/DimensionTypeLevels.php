<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\DimensionType\FinancialsField;
use PhpTwinfield\Fields\Level1234\DimensionType\TimeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionTypeLevels extends BaseObject
{
    use FinancialsField;
    use TimeField;
}
