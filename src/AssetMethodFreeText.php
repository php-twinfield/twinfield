<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\AssetMethod\FreeTextTypeField;
use PhpTwinfield\Fields\ElementValueField;
use PhpTwinfield\Fields\IDField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethodFreeText extends BaseObject
{
    use ElementValueField;
    use FreeTextTypeField;
    use IDField;
}
