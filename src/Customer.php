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
use PhpTwinfield\Fields\Level1234\Level2\Customer\DiscountArticleField;
use PhpTwinfield\Fields\Level1234\Level2\Customer\DiscountArticleIDField;
use PhpTwinfield\Fields\Level1234\Level2\PaymentConditionDiscountDaysField;
use PhpTwinfield\Fields\Level1234\Level2\PaymentConditionDiscountPercentageField;
use PhpTwinfield\Fields\Level1234\Level2\RemittanceAdviceSendMailField;
use PhpTwinfield\Fields\Level1234\Level2\RemittanceAdviceSendTypeField;
use PhpTwinfield\Fields\Level1234\Level2\WebsiteField;
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
class Customer extends BaseObject
{
    use BeginYearField;
    use BehaviourField;
    use CodeField;
    use DimensionGroupField;
    use DimensionTypeField;
    use DiscountArticleField;
    use DiscountArticleIDField;
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
        $this->setTypeFromCode('DEB');

        $this->setCreditManagement(new CustomerCreditManagement);
        $this->setFinancials(new CustomerFinancials);
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

    public function addBank(CustomerBank $bank)
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

    public function addPostingRule(CustomerPostingRule $postingRule)
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
