<?php

namespace PhpTwinfield\User;


class User
{
    /**
     * @var string the code of the user
     */
    private $code;
    /**
     * @var string the name of the user
     */
    private $name;
    /**
     * @var string the short name of the user
     */
    private $shortName;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $value
     */
    public function setShortName($value)
    {
        $this->shortName = $value;
    }
}
