<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\AccountTypeField;
use PhpTwinfield\Fields\Dimensions\LevelField;
use PhpTwinfield\Fields\Dimensions\MatchTypeField;
use PhpTwinfield\Fields\Dimensions\SubAnalyseField;
use PhpTwinfield\Fields\Dimensions\SubstitutionLevelField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithIDField;
use PhpTwinfield\Fields\Dimensions\VatCodeFixedField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets
 * @todo Add documentation and typehints to all properties.
 */
class FixedAssetFinancials extends BaseObject
{
    use AccountTypeField;
    use LevelField;
    use MatchTypeField;
    use SubAnalyseField;
    use SubstitutionLevelField;
    use SubstituteWithField;
    use SubstituteWithIDField;
    use VatCodeField;
    use VatCodeFixedField;

    public function __construct()
    {
        $this->setSubstitutionLevel(2);
    }
}
