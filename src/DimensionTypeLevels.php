<?php

namespace PhpTwinfield;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes
 * @todo Add documentation and typehints to all properties.
 */
class DimensionTypeLevels
{
    private $financials;
    private $time;
    private $fixedAssets;
    private $invoices;

    public function getFinancials()
    {
        return $this->financials;
    }

    public function setFinancials($financials)
    {
        $this->financials = $financials;
        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    public function getFixedAssets()
    {
        return $this->fixedAssets;
    }

    public function setFixedAssets($fixedAssets)
    {
        $this->fixedAssets = $fixedAssets;
        return $this;
    }

    public function getInvoices()
    {
        return $this->invoices;
    }

    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
        return $this;
    }
}
