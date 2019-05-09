<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\Level34\BillableField;
use PhpTwinfield\Fields\Level1234\Level34\BillableLockedField;
use PhpTwinfield\Fields\Level1234\Level34\LabelField;
use PhpTwinfield\Fields\Level1234\Level34\MandatoryField;
use PhpTwinfield\Fields\RateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects
 * @todo Add documentation and typehints to all properties.
 */
class ProjectQuantity extends BaseObject
{
    use BillableField;
    use BillableLockedField;
    use LabelField;
    use MandatoryField;
    use RateField;
}
