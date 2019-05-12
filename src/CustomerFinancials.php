<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\Dimensions\AccountTypeField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\CollectionSchemaField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\EBillingField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\EBillMailField;
use PhpTwinfield\Fields\Dimensions\Level2\DueDaysField;
use PhpTwinfield\Fields\Dimensions\Level2\MeansOfPaymentField;
use PhpTwinfield\Fields\Dimensions\Level2\PayAvailableField;
use PhpTwinfield\Fields\Dimensions\Level2\PayCodeField;
use PhpTwinfield\Fields\Dimensions\Level2\PayCodeIDField;
use PhpTwinfield\Fields\Dimensions\LevelField;
use PhpTwinfield\Fields\Dimensions\MatchTypeField;
use PhpTwinfield\Fields\Dimensions\SubAnalyseField;
use PhpTwinfield\Fields\Dimensions\SubstitutionLevelField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithField;
use PhpTwinfield\Fields\Dimensions\SubstituteWithIDField;
use PhpTwinfield\Fields\Dimensions\VatCodeFixedField;
use PhpTwinfield\Fields\VatCodeField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class CustomerFinancials extends BaseObject
{
    use AccountTypeField;
    use CollectionSchemaField;
    use DueDaysField;
    use EBillingField;
    use EBillMailField;
    use LevelField;
    use MatchTypeField;
    use MeansOfPaymentField;
    use PayAvailableField;
    use PayCodeField;
    use PayCodeIDField;
    use SubAnalyseField;
    use SubstitutionLevelField;
    use SubstituteWithField;
    use SubstituteWithIDField;
    use VatCodeField;
    use VatCodeFixedField;

    private $collectMandate;
    private $childValidations = [];

    public function __construct()
    {
        $this->setAccountTypeFromString('inherit');
        $this->setCollectionSchemaFromString('core');
        $this->setDueDays(30);
        $this->setMatchTypeFromString('customersupplier');
        $this->setSubAnalyseFromString('false');
        $this->setSubstitutionLevel(1);

        $this->setCollectMandate(new CustomerCollectMandate);
    }

    public function getCollectMandate(): CustomerCollectMandate
    {
        return $this->collectMandate;
    }

    public function setCollectMandate(CustomerCollectMandate $collectMandate)
    {
        $this->collectMandate = $collectMandate;
        return $this;
    }

    public function getChildValidations()
    {
        return $this->childValidations;
    }

    public function addChildValidation(CustomerChildValidation $childValidation)
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
