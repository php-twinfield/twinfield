<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\DimensionGroup\CodeField;
use PhpTwinfield\Fields\Level1234\DimensionType\TypeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups
 * @todo Add documentation and typehints to all properties.
 */
class DimensionGroupDimension extends BaseObject
{
    use CodeField;
    use TypeField;
}
