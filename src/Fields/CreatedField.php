<?php

namespace PhpTwinfield\Fields;

/**
 * Created field
 * Used by: AssetMethod, Office, Rate, User, VatCode, VatCodePercentage
 *
 * @package PhpTwinfield\Traits
 */
trait CreatedField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $created;

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param \DateTimeInterface|null $created
     * @return $this
     */
    public function setCreated(?\DateTimeInterface $created)
    {
        $this->created = $created;
        return $this;
    }
}