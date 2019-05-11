<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\IDField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\Invoice\Article\FreeText1Field;
use PhpTwinfield\Fields\Invoice\Article\FreeText2Field;
use PhpTwinfield\Fields\Invoice\Article\SubCodeField;
use PhpTwinfield\Fields\Invoice\UnitsField;
use PhpTwinfield\Fields\Invoice\UnitsPriceExclField;
use PhpTwinfield\Fields\Invoice\UnitsPriceIncField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles
 * @todo Add documentation and typehints to all properties.
 */
class ArticleLine extends BaseObject
{
    use FreeText1Field;
    use FreeText2Field;
    use FreeText3Field;
    use IDField;
    use InUseField;
    use NameField;
    use ShortNameField;
    use StatusField;
    use SubCodeField;
    use UnitsField;
    use UnitsPriceExclField;
    use UnitsPriceIncField;
}
