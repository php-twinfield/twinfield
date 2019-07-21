<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\AccountTypeField;
use PhpTwinfield\Fields\Dimensions\Level2\DueDaysField;
use PhpTwinfield\Fields\Dimensions\Level2\Supplier\RelationsReferenceField;
use PhpTwinfield\Fields\Dimensions\Level2\MeansOfPaymentField;
use PhpTwinfield\Fields\Dimensions\Level2\PayAvailableField;
use PhpTwinfield\Fields\Dimensions\Level2\PayCodeField;
use PhpTwinfield\Fields\Dimensions\Level2\PayCodeIDField;
use PhpTwinfield\Fields\Dimensions\MatchTypeField;
use PhpTwinfield\Fields\Dimensions\SubAnalyseField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithIDField;
use PhpTwinfield\Fields\Dimensions\SubstitutionLevelField;
use PhpTwinfield\Fields\Dimensions\VatCodeFixedField;
use PhpTwinfield\Fields\LevelField;
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
    use SubstituteWithField;
    use SubstituteWithIDField;
    use SubstitutionLevelField;
    use VatCodeField;
    use VatCodeFixedField;

    private $childValidations = [];

    public function __construct()
    {
        $this->setAccountType(\PhpTwinfield\Enums\AccountType::INHERIT());
        $this->setDueDays(30);
        $this->setMatchType(\PhpTwinfield\Enums\MatchType::CUSTOMERSUPPLIER());
        $this->setSubAnalyse(\PhpTwinfield\Enums\SubAnalyse::FALSE());
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
