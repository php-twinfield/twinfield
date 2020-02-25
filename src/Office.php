<?php

namespace PhpTwinfield;

class Office
{
    /**
     * @var string|null The code of the office.
     */
    private $code;

    /**
     * @var string|null The code of the country of the office.
     */
    private $countryCode;

    /**
     * @var string|null The name of the office.
     */
    private $name;

    public static function fromCode(string $code) {
        $instance = new self;
        $instance->setCode($code);

        return $instance;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->getCode();
    }
}
