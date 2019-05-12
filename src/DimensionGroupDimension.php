<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\DimensionGroup\CodeField;
use PhpTwinfield\Fields\Dimensions\DimensionType\TypeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups
 * @todo Add documentation and typehints to all properties.
 */
class DimensionGroupDimension extends BaseObject
{
    use CodeField;
    use TypeField;
}
