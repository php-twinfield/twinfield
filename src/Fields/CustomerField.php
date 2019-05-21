<?php

namespace PhpTwinfield\Fields;

use PhpTwinfield\Customer;

/**
 * The customer
 * Used by: ActivityProjects, Invoice, ProjectProjects
 *
 * @package PhpTwinfield\Traits
 */
trait CustomerField
{
    /**
     * @var Customer|null
     */
    private $customer;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function getCustomerToString(): ?string
    {
        if ($this->getCustomer() != null) {
            return $this->customer->getCode();
        } else {
            return null;
        }
    }

    /**
     * @return $this
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @param string|null $customerString
     * @return $this
     * @throws Exception
     */
    public function setCustomerFromString(?string $customerString)
    {
        $customer = new Customer();
        $customer->setCode($customerString);
        return $this->setCustomer($customer);
    }
}
