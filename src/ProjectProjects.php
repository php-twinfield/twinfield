<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CustomerField;
use PhpTwinfield\Fields\Level1234\Level34\AuthoriserField;
use PhpTwinfield\Fields\Level1234\Level34\AuthoriserInheritField;
use PhpTwinfield\Fields\Level1234\Level34\AuthoriserLockedField;
use PhpTwinfield\Fields\Level1234\Level34\BillableField;
use PhpTwinfield\Fields\Level1234\Level34\BillableForRatioField;
use PhpTwinfield\Fields\Level1234\Level34\BillableInheritField;
use PhpTwinfield\Fields\Level1234\Level34\BillableLockedField;
use PhpTwinfield\Fields\Level1234\Level34\CustomerInheritField;
use PhpTwinfield\Fields\Level1234\Level34\CustomerLockedField;
use PhpTwinfield\Fields\Level1234\Level34\InvoiceDescriptionField;
use PhpTwinfield\Fields\Level1234\Level34\RateInheritField;
use PhpTwinfield\Fields\Level1234\Level34\RateLockedField;
use PhpTwinfield\Fields\Level1234\Level34\ValidFromField;
use PhpTwinfield\Fields\Level1234\Level34\ValidTillField;
use PhpTwinfield\Fields\RateField;

/**
 * @see https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects
 * @todo Add documentation and typehints to all properties.
 */
class ProjectProjects extends BaseObject
{
    use AuthoriserField;
    use AuthoriserInheritField;
    use AuthoriserLockedField;
    use BillableField;
    use BillableForRatioField;
    use BillableInheritField;
    use BillableLockedField;
    use CustomerField;
    use CustomerInheritField;
    use CustomerLockedField;
    use InvoiceDescriptionField;
    use RateField;
    use RateInheritField;
    use RateLockedField;
    use ValidFromField;
    use ValidTillField;

    private $quantities = [];

    public function __construct()
    {
        $this->setAuthoriserInherit(true);
        $this->setAuthoriserLocked(true);
        $this->setBillableInherit(true);
        $this->setBillableLocked(true);
        $this->setCustomerInherit(true);
        $this->setCustomerLocked(true);
        $this->setRateInherit(true);
        $this->setRateLocked(true);
    }

    public function getQuantities()
    {
        return $this->quantities;
    }

    public function addQuantity(ProjectQuantity $quantity)
    {
        $this->quantities[] = $quantity;
        return $this;
    }

    public function removeQuantity($index)
    {
        if (array_key_exists($index, $this->quantities)) {
            unset($this->quantities[$index]);
            return true;
        } else {
            return false;
        }
    }
}
