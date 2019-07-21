<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\Level34\BillableField;
use PhpTwinfield\Fields\Dimensions\Level34\BillableLockedField;
use PhpTwinfield\Fields\Dimensions\Level34\LabelField;
use PhpTwinfield\Fields\Dimensions\Level34\MandatoryField;
use PhpTwinfield\Fields\RateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities
 * @todo Add documentation and typehints to all properties.
 */
class ActivityQuantity extends BaseObject
{
    use BillableField;
    use BillableLockedField;
    use LabelField;
    use MandatoryField;
    use RateField;
}
