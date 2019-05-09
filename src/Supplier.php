<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\Level1234\BeginYearField;
use PhpTwinfield\Fields\Level1234\DimensionGroupField;
use PhpTwinfield\Fields\Level1234\DimensionTypeField;
use PhpTwinfield\Fields\Level1234\EndYearField;
use PhpTwinfield\Fields\Level1234\GeneralLedgerBeginPeriodField;
use PhpTwinfield\Fields\Level1234\GeneralLedgerEndPeriodField;
use PhpTwinfield\Fields\Level1234\Level2\PaymentConditionDiscountDaysField;
use PhpTwinfield\Fields\Level1234\Level2\PaymentConditionDiscountPercentageField;
use PhpTwinfield\Fields\Level1234\Level2\RemittanceAdviceSendMailField;
use PhpTwinfield\Fields\Level1234\Level2\RemittanceAdviceSendTypeField;
use PhpTwinfield\Fields\Level1234\Level2\Supplier\BlockedAccountPaymentConditionsIncludeVatField;
use PhpTwinfield\Fields\Level1234\Level2\Supplier\BlockedAccountPaymentConditionsPercentageField;
use PhpTwinfield\Fields\Level1234\Level2\WebsiteField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers
 * @todo Add documentation and typehints to all properties.
 */
class Supplier extends BaseObject
{
    use BeginYearField;
    use BehaviourField;
    use BlockedAccountPaymentConditionsIncludeVatField;
    use BlockedAccountPaymentConditionsPercentageField;
    use CodeField;
    use DimensionGroupField;
    use DimensionTypeField;
    use EndYearField;
    use GeneralLedgerBeginPeriodField;
    use GeneralLedgerEndPeriodField;
    use InUseField;
    use NameField;
    use OfficeField;
    use PaymentConditionDiscountDaysField;
    use PaymentConditionDiscountPercentageField;
    use RemittanceAdviceSendMailField;
    use RemittanceAdviceSendTypeField;
    use ShortNameField;
    use StatusField;
    use TouchedField;
    use UIDField;
    use WebsiteField;

    private $financials;
    private $addresses = [];
    private $banks = [];
    private $postingRules = [];

    public function __construct()
    {
        $this->setBeginPeriod(0);
        $this->setBeginYear(0);
        $this->setEndPeriod(0);
        $this->setEndYear(0);
        $this->setTypeFromCode('CRD');

        $this->setFinancials(new SupplierFinancials);
    }

    public function getFinancials(): SupplierFinancials
    {
        return $this->financials;
    }

    public function setFinancials(SupplierFinancials $financials)
    {
        $this->financials = $financials;
        return $this;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function addAddress(SupplierAddress $address)
    {
        $this->addresses[$address->getID()] = $address;
        return $this;
    }

    public function removeAddress($index)
    {
        if (array_key_exists($index, $this->addresses)) {
            unset($this->addresses[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function getBanks()
    {
        return $this->banks;
    }

    public function addBank(SupplierBank $bank)
    {
        $this->banks[$bank->getID()] = $bank;
        return $this;
    }

    public function removeBank($index)
    {
        if (array_key_exists($index, $this->banks)) {
            unset($this->banks[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function getPostingRules()
    {
        return $this->postingRules;
    }

    public function addPostingRule(SupplierPostingRule $postingRule)
    {
        $this->postingRules[$postingRule->getID()] = $postingRule;
        return $this;
    }

    public function removePostingRule($index)
    {
        if (array_key_exists($index, $this->postingRules)) {
            unset($this->postingRules[$index]);
            return true;
        } else {
            return false;
        }
    }
}
