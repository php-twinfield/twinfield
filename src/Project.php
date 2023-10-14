<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\ProjectStatus;
use PhpTwinfield\Fields\BehaviourField;
use PhpTwinfield\Fields\InUseField;
use PhpTwinfield\Fields\TouchedField;
use PhpTwinfield\Fields\UIDField;
use PhpTwinfield\Transactions\TransactionFields\OfficeField;
use Webmozart\Assert\Assert;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects
 */
class Project extends BaseObject
{
    use OfficeField;
    use InUseField;
    use UIDField;
    use BehaviourField;
    use TouchedField;

    private $status;
    private $validFrom;
    private $validTo;
    private $invoiceDescription;
    private $authoriser;
    private $customer;
    private $billable;
    private $rate;
    private $quantities;
    private $name;
    private $shortName;
    private $code;
    private $type;
    private $beginPeriod;
    private $beginYear;
    private $endPeriod;
    private $endYear;

    /**
     * Get the beginPeriod attribute
     * @return mixed
     */
    public function getBeginPeriod()
    {
        return $this->beginPeriod;
    }

    /**
     * @param mixed $beginPeriod
     * @return Project
     */
    public function setBeginPeriod($beginPeriod)
    {
        $this->beginPeriod = $beginPeriod;
        return $this;
    }

    /**
     * Get the beginYear attribute
     * @return mixed
     */
    public function getBeginYear()
    {
        return $this->beginYear;
    }

    /**
     * @param mixed $beginYear
     * @return Project
     */
    public function setBeginYear($beginYear)
    {
        $this->beginYear = $beginYear;
        return $this;
    }

    /**
     * Get the endPeriod attribute
     * @return mixed
     */
    public function getEndPeriod()
    {
        return $this->endPeriod;
    }

    /**
     * @param mixed $endPeriod
     * @return Project
     */
    public function setEndPeriod($endPeriod)
    {
        $this->endPeriod = $endPeriod;
        return $this;
    }

    /**
     * Get the endYear attribute
     * @return mixed
     */
    public function getEndYear()
    {
        return $this->endYear;
    }

    /**
     * @param mixed $endYear
     * @return Project
     */
    public function setEndYear($endYear)
    {
        $this->endYear = $endYear;
        return $this;
    }

    /**
     * Get the code attribute
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Project
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get the shortName attribute
     * @return mixed
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Short name of the project
     * Should be 20 chars or less
     * @param string $shortName
     * @return Project
     */
    public function setShortName(string $shortName)
    {
        Assert::maxLength($shortName, 20);

        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get the name attribute
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name of the project
     * Should be 80 chars or less
     * @param string $name
     * @return Project
     */
    public function setName(string $name)
    {
        Assert::maxLength($name, 80);

        $this->name = $name;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setValidFrom(\DateTimeInterface $date)
    {
        $this->validFrom = $date;
        return $this;
    }

    /**
     * @param string $dateString
     * @return $this
     * @throws Exception
     */
    public function setValidFromString(?string $dateString)
    {
        if(is_null($dateString)) {
            $this->validTo = null;
            return $this;
        }

        $this->setValidFrom(Util::parseDate($dateString));
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getValidTo(): ?\DateTimeInterface
    {
        return $this->validTo;
    }

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function setValidTo(\DateTimeInterface $date)
    {
        $this->validTo = $date;
        return $this;
    }

    /**
     * @param string $dateString
     * @return $this
     * @throws Exception
     */
    public function setValidToString(?string $dateString)
    {
        if(is_null($dateString)) {
            $this->validTo = null;
            return $this;
        }

        $this->setValidTo(Util::parseDate($dateString));
        return $this;
    }

    /**
     * Get the invoiceDescription attribute
     * @return mixed
     */
    public function getInvoiceDescription()
    {
        return $this->invoiceDescription;
    }

    /**
     * This field can be used to enter a longer project description which will be available on the invoice template.
     * @param string $invoiceDescription
     * @return Project
     */
    public function setInvoiceDescription(string $invoiceDescription)
    {
        $this->invoiceDescription = $invoiceDescription;
        return $this;
    }

    /**
     * Get the authoriser attribute
     * @return mixed
     */
    public function getAuthoriser()
    {
        return $this->authoriser;
    }

    /**
     * A specific authoriser for a project.
     * @param mixed $authoriser
     * @return Project
     */
    public function setAuthoriser($authoriser)
    {
        $this->authoriser = $authoriser;
        return $this;
    }

    /**
     * Get the customer attribute
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * A project always needs to be linked to a customer.
     * Choose to have the customer ‘inherited’ (from an activity) or you can specify the customer here.
     * @param mixed $customer
     * @return Project
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get the billable attribute
     * @return mixed
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * @param bool $billable
     * @return Project
     */
    public function setBillable(bool $billable)
    {
        $this->billable = $billable;
        return $this;
    }

    /**
     * Get the rate attribute
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return Project
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Get the quantities attribute
     * @return array
     */
    public function getQuantities()
    {
        return $this->quantities;
    }

    /**
     * @param array $quantities
     * @return Project
     */
    public function setQuantities(array $quantities)
    {
        Assert::allIsInstanceOf($quantities, ProjectQuantity::class);

        $this->quantities = $quantities;

        return $this;
    }

    /**
     * @param ProjectQuantity $quantity
     * @return Project
     */
    public function addQuantity(ProjectQuantity $quantity)
    {
        $this->quantities[] = $quantity;

        return $this;
    }

    /**
     * Get the status attribute
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the status attribute
     * @param ProjectStatus $status
     * @return Project
     */
    public function setStatus(ProjectStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the type attribute
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type attribute
     * @param string $type
     * @return Project
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }
}
