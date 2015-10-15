<?php

namespace Pronamic\Twinfield\Office;

class Office
{
    /**
     * @var string the code of the office
     */
    private $code;
    /**
     * @var string the code of the country of the office
     */
    private $countryCode;
    /**
     * @var string the name of the office
     */
    private $name;

    public function getCode()
    {
        return $this->code;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}