<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\CostCenter;

/**
 * The Document Holder for making new XML CostCenter. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new CostCenter.
 *
 * @package PhpTwinfield
 * @subpackage CostCenter\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class CostCentersDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $costCenterElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * costCenterElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->costCenterElement = $this->createElement('dimension');
        $this->appendChild($this->costCenterElement);
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
        $rootElement = $this->costCenterElement;

        $status = $costCenter->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // CostCenter elements and their methods
        $costCenterTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'type'              => 'getType',
        );

        // Go through each CostCenter element and use the assigned method
        foreach ($costCenterTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $costCenter->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $rootElement->appendChild($element);
        }
    }
}
