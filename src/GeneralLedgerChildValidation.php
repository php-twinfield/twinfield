<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\ElementValueField;
use PhpTwinfield\Fields\Level1234\LevelField;
use PhpTwinfield\Fields\Level1234\TypeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets
 * @todo Add documentation and typehints to all properties.
 */
class GeneralLedgerChildValidation extends BaseObject
{
    use ElementValueField;
    use LevelField;
    use TypeField;
}
