<?php

namespace Pronamic\Twinfield\User;


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

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

}