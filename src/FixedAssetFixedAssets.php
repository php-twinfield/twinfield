<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\FreeText1Field;
use PhpTwinfield\Fields\FreeText2Field;
use PhpTwinfield\Fields\FreeText3Field;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\BeginPeriodField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\BeginPeriodLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText1LockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText2LockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText3LockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText4Field;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText4LockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText5Field;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\FreeText5LockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\LastDepreciationField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\LastDepreciationLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\MethodField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\MethodLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\NrOfPeriodsInheritedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\NrOfPeriodsLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\PercentageLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\PurchaseDateField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\PurchaseDateLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\ResidualValueField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\ResidualValueLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\SellDateField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\SellDateLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\StatusField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\StatusLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\StopValueField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\StopValueLockedField;
use PhpTwinfield\Fields\Level1234\Level34\FixedAsset\TransactionLinesLockedField;
use PhpTwinfield\Fields\NrOfPeriodsField;
use PhpTwinfield\Fields\PercentageField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetFixedAssets extends BaseObject
{
    use BeginPeriodField;
    use BeginPeriodLockedField;
    use FreeText1Field;
    use FreeText1LockedField;
    use FreeText2Field;
    use FreeText2LockedField;
    use FreeText3Field;
    use FreeText3LockedField;
    use FreeText4Field;
    use FreeText4LockedField;
    use FreeText5Field;
    use FreeText5LockedField;
    use LastDepreciationField;
    use LastDepreciationLockedField;
    use MethodField;
    use MethodLockedField;
    use NrOfPeriodsField;
    use NrOfPeriodsInheritedField;
    use NrOfPeriodsLockedField;
    use PercentageField;
    use PercentageLockedField;
    use PurchaseDateField;
    use PurchaseDateLockedField;
    use ResidualValueField;
    use ResidualValueLockedField;
    use SellDateField;
    use SellDateLockedField;
    use StatusField;
    use StatusLockedField;
    use StopValueField;
    use StopValueLockedField;
    use TransactionLinesLockedField;

    private $transactionLines = [];

    public function getTransactionLines()
    {
        return $this->transactionLines;
    }

    public function addTransactionLine(FixedAssetTransactionLine $transactionLine)
    {
        $this->transactionLines[] = $transactionLine;
        return $this;
    }

    public function removeTransactionLine($index)
    {
        if (array_key_exists($index, $this->transactionLines)) {
            unset($this->transactionLines[$index]);
            return true;
        } else {
            return false;
        }
    }
}
