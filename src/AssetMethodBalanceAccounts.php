<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\AssetMethod\AssetsToActivateField;
use PhpTwinfield\Fields\AssetMethod\DepreciationField;
use PhpTwinfield\Fields\AssetMethod\DepreciationGroupField;
use PhpTwinfield\Fields\AssetMethod\PurchaseValueField;
use PhpTwinfield\Fields\AssetMethod\PurchaseValueGroupField;
use PhpTwinfield\Fields\AssetMethod\ReconciliationField;
use PhpTwinfield\Fields\AssetMethod\ToBeInvoicedField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods
 * @todo Add documentation and typehints to all properties.
 */
class AssetMethodBalanceAccounts extends BaseObject
{
    use AssetsToActivateField;
    use DepreciationField;
    use DepreciationGroupField;
    use PurchaseValueField;
    use PurchaseValueGroupField;
    use ReconciliationField;
    use ToBeInvoicedField;
}
