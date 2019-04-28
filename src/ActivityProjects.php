<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities
 * @todo Add documentation and typehints to all properties.
 */
class ActivityProjects
{
    private $authoriser;
    private $billable;
    private $customer;
    private $invoiceDescription;
    private $rate;
    private $validFrom;
    private $validTill;
    private $quantities = [];

    public function getAuthoriser()
    {
        return $this->authoriser;
    }

    public function setAuthoriser($authoriser)
    {
        $this->authoriser = $authoriser;
        return $this;
    }

    public function getBillable()
    {
        return $this->billable;
    }

    public function setBillable($billable)
    {
        $this->billable = $billable;
        return $this;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function getInvoiceDescription()
    {
        return $this->invoiceDescription;
    }

    public function setInvoiceDescription($invoiceDescription)
    {
        $this->invoiceDescription = $invoiceDescription;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    public function getValidFrom()
    {
        return $this->validFrom;
    }

    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidTill()
    {
        return $this->validTill;
    }

    public function setValidTill($validTill)
    {
        $this->validTill = $validTill;
        return $this;
    }

    public function getQuantities()
    {
        return $this->quantities;
    }

    public function addQuantity(ActivityQuantity $quantity)
    {
        $this->quantities[] = $quantity;
        return $this;
    }

    public function removeQuantity($index)
    {
        if (array_key_exists($index, $this->quantities)) {
            unset($this->quantities[$index]);
            return true;
        } else {
            return false;
        }
    }
}
