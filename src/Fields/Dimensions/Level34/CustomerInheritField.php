<?php

namespace PhpTwinfield\Fields\Dimensions\Level34;

trait CustomerInheritField
{
    /**
     * Customer inherit field
     * Used by: ActivityProjects, ProjectsProjects
     *
     * @var bool
     */
    private $customerInherit;

    /**
     * @return bool
     */
    public function getCustomerInherit(): ?bool
    {
        return $this->customerInherit;
    }

    public function getCustomerInheritToString(): ?string
    {
        return ($this->getCustomerInherit()) ? 'true' : 'false';
    }

    /**
     * @param bool $customerInherit
     * @return $this
     */
    public function setCustomerInherit(?bool $customerInherit): self
    {
        $this->customerInherit = $customerInherit;
        return $this;
    }

    /**
     * @param string|null $customerInheritString
     * @return $this
     * @throws Exception
     */
    public function setCustomerInheritFromString(?string $customerInheritString)
    {
        return $this->setCustomerInherit(filter_var($customerInheritString, FILTER_VALIDATE_BOOLEAN));
    }
}