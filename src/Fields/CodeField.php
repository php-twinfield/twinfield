<?php

namespace PhpTwinfield\Fields;

trait CodeField
{
    /**
     * Code field
     *
     * @var string|null
     */
    private $code;

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     * @return $this
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;
        return $this;
    }
}
