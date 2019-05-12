<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

use PhpTwinfield\Exception;
use PhpTwinfield\Util;

/**
 * Valid from field
 * Used by: ActivityProjects, ProjectProjects
 *
 * @package PhpTwinfield\Traits
 * @see Util::formatDate()
 * @see Util::parseDate()
 */
trait ValidFromField
{
    /**
     * @var \DateTimeInterface|null
     */
    private $validFrom;

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    /**
     * @return string|null
     */
    public function getValidFromToString(): ?string
    {
        if ($this->getValidFrom() != null) {
            return Util::formatDate($this->getValidFrom());
        } else {
            return null;
        }
    }

    /**
     * @param \DateTimeInterface|null $validFrom
     * @return $this
     */
    public function setValidFrom(?\DateTimeInterface $validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    /**
     * @param string|null $validFromString
     * @return $this
     * @throws Exception
     */
    public function setValidFromFromString(?string $validFromString)
    {
        if ((bool)strtotime($validFromString)) {
            return $this->setValidFrom(Util::parseDate($validFromString));
        } else {
            return $this->setValidFrom(null);
        }
    }
}