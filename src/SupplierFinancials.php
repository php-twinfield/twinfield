<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Level1234\AccountTypeField;
use PhpTwinfield\Fields\Level1234\Level2\DueDaysField;
use PhpTwinfield\Fields\Level1234\Level2\Supplier\RelationsReferenceField;
use PhpTwinfield\Fields\Level1234\Level2\MeansOfPaymentField;
use PhpTwinfield\Fields\Level1234\Level2\PayAvailableField;
use PhpTwinfield\Fields\Level1234\Level2\PayCodeField;
use PhpTwinfield\Fields\Level1234\Level2\PayCodeIDField;
use PhpTwinfield\Fields\Level1234\LevelField;
use PhpTwinfield\Fields\Level1234\MatchTypeField;
use PhpTwinfield\Fields\Level1234\SubAnalyseField;
use PhpTwinfield\Fields\Level1234\SubstitutionLevelField;
use PhpTwinfield\Fields\Level1234\SubstituteWithField;
use PhpTwinfield\Fields\Level1234\SubstituteWithIDField;
use PhpTwinfield\Fields\Level1234\VatCodeFixedField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers
 * @todo Add documentation and typehints to all properties.
 */
class SupplierFinancials extends BaseObject
{
    use AccountTypeField;
    use DueDaysField;
    use LevelField;
    use MatchTypeField;
    use MeansOfPaymentField;
    use PayAvailableField;
    use PayCodeField;
    use PayCodeIDField;
    use RelationsReferenceField;
    use SubAnalyseField;
    use SubstitutionLevelField;
    use SubstituteWithField;
    use SubstituteWithIDField;
    use VatCodeField;
    use VatCodeFixedField;

    private $childValidations = [];

    public function __construct()
    {
        $this->setAccountTypeFromString('inherit');
        $this->setDueDays(30);
        $this->setMatchTypeFromString('customersupplier');
        $this->setSubAnalyseFromString('false');
        $this->setSubstitutionLevel(1);
    }

    public function getChildValidations()
    {
        return $this->childValidations;
    }

    public function addChildValidation(SupplierChildValidation $childValidation)
    {
        $this->childValidations[] = $childValidation;
        return $this;
    }

    public function removeChildValidation($index)
    {
        if (array_key_exists($index, $this->childValidations)) {
            unset($this->childValidations[$index]);
            return true;
        } else {
            return false;
        }
    }
}
