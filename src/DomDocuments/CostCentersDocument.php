<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\CostCenter;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML CostCenter. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new CostCenter.
 *
 * @package PhpTwinfield
 * @subpackage CostCenter\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class CostCentersDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
    }

    /**
     * Turns a passed CostCenter class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the CostCenter to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param CostCenter $costCenter
     * @return void | [Adds to this instance]
     */
    public function addCostCenter(CostCenter $costCenter)
    {
        $costCenterElement = $this->createElement('dimension');
        $this->rootElement->appendChild($costCenterElement);

        $status = $costCenter->getStatus();

        if (!empty($status)) {
            $costCenterElement->setAttribute('status', $status);
        }

        if (!empty($costCenter->getCode())) {
            $costCenterElement->appendChild($this->createNodeWithTextContent('code', $costCenter->getCode()));
        }

        $costCenterElement->appendChild($this->createNodeWithTextContent('name', $costCenter->getName()));
        $costCenterElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($costCenter->getOffice())));
        $costCenterElement->appendChild($this->createNodeWithTextContent('type', Util::objectToStr($costCenter->getType())));
    }
}
