<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\AssetMethod\DepreciationField;
use PhpTwinfield\Fields\AssetMethod\ReconciliationField;
use PhpTwinfield\Fields\AssetMethod\SalesField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethodProfitLossAccounts extends BaseObject
{
    use DepreciationField;
    use ReconciliationField;
    use SalesField;
}
