<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Office;

/**
 * The office
 *
 * @package PhpTwinfield\Traits
 */
trait OfficeField
{
    /**
     * @var Office|null
     */
    private $office;

    public function getOffice(): ?Office
    {
        return $this->office;
    }

    public function getOfficeToCode(): ?string
    {
        if ($this->getOffice() != null) {
            return $this->office->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setOffice(?Office $office): self
    {
        $this->office = $office;
        return $this;
    }

    /**
     * @param string|null $officeCode
     * @return $this
     * @throws Exception
     */
    public function setOfficeFromCode(?string $officeCode)
    {
        $office = new Office();
        $office->setCode($officeCode);
        return $this->setOffice($office);
    }
}
