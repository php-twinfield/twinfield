<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\AccountTypeField;
use PhpTwinfield\Fields\Level1234\LevelField;
use PhpTwinfield\Fields\Level1234\MatchTypeField;
use PhpTwinfield\Fields\Level1234\SubAnalyseField;
use PhpTwinfield\Fields\Level1234\SubstitutionLevelField;
use PhpTwinfield\Fields\Level1234\SubstituteWithField;
use PhpTwinfield\Fields\Level1234\SubstituteWithIDField;
use PhpTwinfield\Fields\Level1234\VatCodeFixedField;
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
