<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\DimensionType\Label1Field;
use PhpTwinfield\Fields\Level1234\DimensionType\Label2Field;
use PhpTwinfield\Fields\Level1234\DimensionType\Label3Field;
use PhpTwinfield\Fields\Level1234\DimensionType\Label4Field;
use PhpTwinfield\Fields\Level1234\DimensionType\Label5Field;
use PhpTwinfield\Fields\Level1234\DimensionType\Label6Field;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionTypeAddress extends BaseObject
{
    use Label1Field;
    use Label2Field;
    use Label3Field;
    use Label4Field;
    use Label5Field;
    use Label6Field;
}
