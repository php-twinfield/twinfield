<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Valid till field
 * Used by: ActivityProjects, ProjectProjects
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait ValidTillField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $validTill;

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidTill(): ?\DateTimeInterface
    {
        return $this->validTill;
    }

    /**
     * @return string|null
     */
    public function getValidTillToString(): ?string
    {
        if ($this->getValidTill() != null) {
            return Util::formatDate($this->getValidTill());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $validTill
     * @return $this
     */
    public function setValidTill(?\DateTimeInterface $validTill)
    {
        $this->validTill = $validTill;
        return $this;
    }

    /**
     * @param string|null $validTillString
     * @return $this
     * @throws Exception
     */
    public function setValidTillFromString(?string $validTillString)
    {
        if ((bool)strtotime($validTillString)) {
            return $this->setValidTill(Util::parseDate($validTillString));
        } else {
            return $this->setValidTill(null);
        }
    }
}