<?php

namespace Pronamic\Twinfield\Customer;

class Customer
{
    private $code;
    private $UID;
    private $status;
    private $name;
    private $type;
    private $inUse;
    private $behaviour;
    private $touched;
    private $beginPeriod;
    private $beginYear;
    private $endPeriod;
    private $endYear;
    private $website;
    private $cocNumber;
    private $vatNumber;
    private $editDimensionName;
    private $dueDays = 0;
    private $payAvailable = 'false';
    private $payCode;
    private $vatCode;
    private $eBilling = 'false';
    private $eBillMail;
    private $creditManagement;
    private $addresses = array();
    private $banks = array();
    private $groups;

    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    public function getID()
    {
        trigger_error('getID is a deprecated function: Use getCode', E_USER_NOTICE);
        return $this->getCode();
    }

    public function setID($ID)
    {
        trigger_error('setID is a deprecated function: Use setCode', E_USER_NOTICE);
        return $this->setCode($ID);
    }

    public function getUID()
    {
        return $this->UID;
    }

    public function setUID($UID)
    {
        $this->UID = $UID;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getInUse()
    {
        return $this->inUse;
    }

    public function setInUse($inUse)
    {
        $this->inUse = $inUse;
        return $this;
    }

    public function getBehaviour()
    {
        return $this->behaviour;
    }

    public function setBehaviour($behaviour)
    {
        $this->behaviour = $behaviour;
        return $this;
    }

    public function getTouched()
    {
        return $this->touched;
    }

    public function setTouched($touched)
    {
        $this->touched = $touched;
        return $this;
    }

    public function getBeginPeriod()
    {
        return $this->beginPeriod;
    }

    public function setBeginPeriod($beginPeriod)
    {
        $this->beginPeriod = $beginPeriod;
        return $this;
    }

    public function getBeginYear()
    {
        return $this->beginYear;
    }

    public function setBeginYear($beginYear)
    {
        $this->beginYear = $beginYear;
        return $this;
    }

    public function getEndPeriod()
    {
        return $this->endPeriod;
    }

    public function setEndPeriod($endPeriod)
    {
        $this->endPeriod = $endPeriod;
        return $this;
    }

    public function getEndYear()
    {
        return $this->endYear;
    }

    public function setEndYear($endYear)
    {
        $this->endYear = $endYear;
        return $this;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    public function getCocNumber()
    {
        return $this->cocNumber;
    }

    public function setCocNumber($cocNumber)
    {
        $this->cocNumber = $cocNumber;
        return $this;
    }

    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

    public function getEditDimensionName()
    {
        return $this->editDimensionName;
    }

    public function setEditDimensionName($editDimensionName)
    {
        $this->editDimensionName = $editDimensionName;
        return $this;
    }

    public function getDueDays()
    {
        return $this->dueDays;
    }

    public function setDueDays($dueDays)
    {
        $this->dueDays = $dueDays;
        return $this;
    }

    public function getPayAvailable()
    {
        return $this->payAvailable;
    }

    public function setPayAvailable($payAvailable)
    {
        $this->payAvailable = var_export($payAvailable, true);
        return $this;
    }

    public function getPayCode()
    {
        return $this->payCode;
    }

    public function setPayCode($payCode)
    {
        $this->payCode = $payCode;
        return $this;
    }

    public function getVatCode()
    {
        return $this->vatCode;
    }

    public function setVatCode($vatCode)
    {
        $this->vatCode = $vatCode;
        return $this;
    }

    public function getEBilling()
    {
        return $this->eBilling;
    }

    public function setEBilling($eBilling)
    {
        $this->eBilling = var_export($eBilling, true);
        return $this;
    }

    public function getEBillMail()
    {
        return $this->eBillMail;
    }

    public function setEBillMail($eBillMail)
    {
        $this->eBillMail = $eBillMail;
        return $this;
    }

    /**
     * 
     * @return CustomerCreditManagement
     */
    public function getCreditManagement()
    {
        return $this->creditManagement;
    }

    public function setCreditManagement(CustomerCreditManagement $creditManagement)
    {
        $this->creditManagement = $creditManagement;
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

    public function getBanks()
    {
        return $this->banks;
    }

    public function addBank(CustomerBank $bank)
    {
        $this->banks[] = $bank;
        return $this;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }
}
