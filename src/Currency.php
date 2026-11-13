<?php

namespace PhpTwinfield;

use PhpTwinfield\Transactions\TransactionFields\OfficeField;

/**
 * @see https://accounting.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies
 * @todo Add documentation and typehints to all properties.
 */
class Currency
{
    use OfficeField;

    private $code;
    private $name;
    private $shortName;
    private $rates = [];

    public function getOfficeCode(): string
    {
        return $this->getOffice()->getCode();
    }
    
    public function setOfficeByCode(string $code): self
    {
        $office = new Office();
        $office->setCode($code);

        $this->setOffice( $office );
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode($code)
    {
        $this->code = $code;
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

    public function getShortName()
    {
        return $this->shortName;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getRates()
    {
        return $this->rates;
    }

    public function addRate(CurrencyRate $rate)
    {
        $this->rates[] = $rate;
        return $this;
    }

    public function removeRate(CurrencyRate $rate)
    {
        $index = array_search($rate, $this->rates);

        if ($index !== false) {
            unset($this->rates[$index]);
            return true;
        } else {
            return false;
        }
    }
}
