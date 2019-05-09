<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\DescriptionField;
use PhpTwinfield\Fields\Level1234\Level2\Dimension1Field;
use PhpTwinfield\Fields\Level1234\Level2\Dimension1IDField;
use PhpTwinfield\Fields\Level1234\Level2\Dimension2Field;
use PhpTwinfield\Fields\Level1234\Level2\Dimension2IDField;
use PhpTwinfield\Fields\Level1234\Level2\Dimension3Field;
use PhpTwinfield\Fields\Level1234\Level2\Dimension3IDField;
use PhpTwinfield\Fields\Level1234\Level2\RatioField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerLine extends BaseObject
{
    use DescriptionField;
    use Dimension1Field;
    use Dimension1IDField;
    use Dimension2Field;
    use Dimension2IDField;
    use Dimension3Field;
    use Dimension3IDField;
    use OfficeField;
    use RatioField;
    use VatCodeField;
}