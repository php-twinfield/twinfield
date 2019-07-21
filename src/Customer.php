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
use PhpTwinfield\Fields\Dimensions\Level2\Customer\DiscountArticleField;
use PhpTwinfield\Fields\Dimensions\Level2\Customer\DiscountArticleIDField;
use PhpTwinfield\Fields\Dimensions\Level2\PaymentConditionDiscountDaysField;
use PhpTwinfield\Fields\Dimensions\Level2\PaymentConditionDiscountPercentageField;
use PhpTwinfield\Fields\Dimensions\Level2\RemittanceAdviceSendMailField;
use PhpTwinfield\Fields\Dimensions\Level2\RemittanceAdviceSendTypeField;
use PhpTwinfield\Fields\Dimensions\Level2\WebsiteField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;
use PhpTwinfield\Fields\StatusField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers
 * @todo Add documentation and typehints to all properties.
 */
class Customer extends BaseObject implements HasCodeInterface
{
    use BeginPeriodField;
    use BeginYearField;
    use BehaviourField;
    use CodeField;
    use DiscountArticleField;
    use DiscountArticleIDField;
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

    private $creditManagement;
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
        $this->setType(\PhpTwinfield\DimensionType::fromCode('DEB'));

        $this->setCreditManagement(new CustomerCreditManagement);
        $this->setFinancials(new CustomerFinancials);
    }

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }

    public function getCreditManagement(): CustomerCreditManagement
    {
        return $this->creditManagement;
    }

    public function setCreditManagement(CustomerCreditManagement $creditManagement)
    {
        $this->creditManagement = $creditManagement;
        return $this;
    }

    public function getFinancials(): CustomerFinancials
    {
        return $this->financials;
    }

    public function setFinancials(CustomerFinancials $financials)
    {
        $this->financials = $financials;
        return $this;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function addAddress(CustomerAddress $address)
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

    public function addBank(CustomerBank $bank)
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

    public function addPostingRule(CustomerPostingRule $postingRule)
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
