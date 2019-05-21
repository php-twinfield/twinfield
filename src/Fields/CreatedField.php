<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Created field
 * Used by: AssetMethod, Office, User, VatCode, VatCodePercentage
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDateTime()
 * @see Util::parseDateTime()
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
     * @return string|null
     */
    public function getCreatedToString(): ?string
    {
        if ($this->getCreated() != null) {
            return Util::formatDateTime($this->getCreated());
        } else {
            return null;
        }
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

    /**
     * @param string|null $createdString
     * @return $this
     * @throws Exception
     */
    public function setCreatedFromString(?string $createdString)
    {
        if ((bool)strtotime($createdString)) {
            return $this->setCreated(Util::parseDateTime($createdString));
        } else {
            return $this->setCreated(null);
        }
    }
}