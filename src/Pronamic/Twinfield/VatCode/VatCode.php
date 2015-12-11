<?php

namespace app\vendor\emilebons\twinfield\src\Pronamic\Twinfield\VatCode;

/**
 * Class VatCode
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield\VatCode
 */
class VatCode
{
    /**
     * @var string the code of the VAT code
     */
    private $code;
    /**
     * @var string the name of the VAT code
     */
    private $name;

    /**
     * @return string the VAT code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string the name of the VAT code
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $code the VAT code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $name the name of the VAT code
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}