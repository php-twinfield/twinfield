<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\Dimensions\BeginPeriodField;
use PhpTwinfield\Fields\Dimensions\BeginYearField;
use PhpTwinfield\Fields\Dimensions\DimensionGroup\GroupField;
use PhpTwinfield\Fields\Dimensions\DimensionType\TypeField;
use PhpTwinfield\Fields\Dimensions\EndPeriodField;
use PhpTwinfield\Fields\Dimensions\EndYearField;
use PhpTwinfield\Fields\Dimensions\Level2\PaymentConditionDiscountDaysField;
use PhpTwinfield\Fields\Dimensions\Level2\PaymentConditionDiscountPercentageField;
use PhpTwinfield\Fields\Dimensions\Level2\RemittanceAdviceSendMailField;
use PhpTwinfield\Fields\Dimensions\Level2\RemittanceAdviceSendTypeField;
use PhpTwinfield\Fields\Dimensions\Level2\Supplier\BlockedAccountPaymentConditionsIncludeVatField;
use PhpTwinfield\Fields\Dimensions\Level2\Supplier\BlockedAccountPaymentConditionsPercentageField;
use PhpTwinfield\Fields\Dimensions\Level2\WebsiteField;
use PhpTwinfield\Fields\InUseField;
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
class Supplier extends BaseObject implements HasCodeInterface
{
    use BeginPeriodField;
    use BeginYearField;
    use BehaviourField;
    use BlockedAccountPaymentConditionsIncludeVatField;
    use BlockedAccountPaymentConditionsPercentageField;
    use CodeField;
    use EndPeriodField;
    use EndYearField;
    use GroupField;
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
    use TypeField;
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
        $this->setType(\PhpTwinfield\DimensionType::fromCode('CRD'));

        $this->setFinancials(new SupplierFinancials);
    }
    
    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
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
        $this->addresses[] = $address;
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
    
    public function removeAddressByID($id)
    {
        $found = false;
                
        foreach ($this->addresses as $index => $address) {
            if ($id == $address->getID()) {
                unset($this->addresses[$index]);
                $found = true;
            }
        }
        
        if ($found) {
            return true;
        }
        
        return false;
    }

    public function getBanks()
    {
        return $this->banks;
    }

    public function addBank(SupplierBank $bank)
    {
        $this->banks[] = $bank;
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
    
    public function removeBankByID($id)
    {
        $found = false;
                
        foreach ($this->banks as $index => $bank) {
            if ($id == $bank->getID()) {
                unset($this->banks[$index]);
                $found = true;
            }
        }
        
        if ($found) {
            return true;
        }
        
        return false;
    }

    public function getPostingRules()
    {
        return $this->postingRules;
    }

    public function addPostingRule(SupplierPostingRule $postingRule)
    {
        $this->postingRules[] = $postingRule;
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
    
    public function removePostingRuleByID($id)
    {
        $found = false;
                
        foreach ($this->postingRules as $index => $postingRule) {
            if ($id == $postingRule->getID()) {
                unset($this->postingRules[$index]);
                $found = true;
            }
        }
        
        if ($found) {
            return true;
        }
        
        return false;
    }
}
