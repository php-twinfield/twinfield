<?php

namespace PhpTwinfield\Currency;

/**
 * Class Currency
 *
 * @author Emile Bons <emile@emilebons.nl>
 */
class Currency
{
    /**
     * @var string The code of the currency, e.g. 'EUR'.
     */
    private $code;

    /**
     * @var string The name of the currency, e.g. 'Euro'.
     */
    private $name;

    /**
     * @var string The short name of the currency, e.g. '€'
     */
    private $shortName;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName)
    {
        $this->shortName = $shortName;
    }
}
