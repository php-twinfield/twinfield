<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\DimensionType\DimensionTypeFinancialsField;
use PhpTwinfield\Fields\Level1234\DimensionType\DimensionTypeTimeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionTypeLevels extends BaseObject
{
    use DimensionTypeFinancialsField;
    use DimensionTypeTimeField;
}
