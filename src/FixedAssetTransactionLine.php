<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dim1Field;
use PhpTwinfield\Fields\Dim2Field;
use PhpTwinfield\Fields\Dim3Field;
use PhpTwinfield\Fields\Dim4Field;
use PhpTwinfield\Fields\Dimensions\AmountField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\AmountLockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\CodeField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\CodeLockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim1LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim2LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim3LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim4LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim5Field;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim5LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim6Field;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\Dim6LockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\LineField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\LineLockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\NumberField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\NumberLockedField;
use PhpTwinfield\Fields\Dimensions\Level34\FixedAsset\PeriodLockedField;
use PhpTwinfield\Fields\PeriodField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetTransactionLine extends BaseObject
{
    use AmountField;
    use AmountLockedField;
    use CodeField;
    use CodeLockedField;
    use Dim1Field;
    use Dim1LockedField;
    use Dim2Field;
    use Dim2LockedField;
    use Dim3Field;
    use Dim3LockedField;
    use Dim4Field;
    use Dim4LockedField;
    use Dim5Field;
    use Dim5LockedField;
    use Dim6Field;
    use Dim6LockedField;
    use LineField;
    use LineLockedField;
    use NumberField;
    use NumberLockedField;
    use PeriodField;
    use PeriodLockedField;
}
