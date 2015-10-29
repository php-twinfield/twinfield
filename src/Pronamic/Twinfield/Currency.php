<?php

namespace Pronamic\Twinfield;

/**
 * Class Currency
 *
 * @author Emile Bons <emile@emilebons.nl>
 * @package Pronamic\Twinfield
 */
class Currency
{
    /**
     * @var string the code of the currency, e.g. 'EUR'
     */
    private $code;
    /**
     * @var string the name of the currency, e.g. 'Euro'
     */
    private $name;
    /**
     * @var string the short name of the currency, e.g. 'Euro'
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
     * @param string $value
     */
    public function setCode($value)
    {
        $this->code = $value;
    }

    /**
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * @param string $value
     */
    public function setShortName($value)
    {
        $this->shortName = $value;
    }
}