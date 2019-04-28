<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\DimensionGroup;

/**
 * The Document Holder for making new XML DimensionGroup. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new DimensionGroup.
 *
 * @package PhpTwinfield
 * @subpackage DimensionGroup\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class DimensionGroupsDocument extends \DOMDocument
{
    /**
     * Holds the <dimensiongroup> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $dimensiongroupElement;

    /**
     * Creates the <dimensiongroup> element and adds it to the property
     * dimensiongroupElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->dimensiongroupElement = $this->createElement('dimensiongroup');
        $this->appendChild($this->dimensiongroupElement);
    }

    /**
     * Turns a passed DimensionGroup class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the DimensionGroup to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param DimensionGroup $dimensiongroup
     * @return void | [Adds to this instance]
     */
    public function addDimensionGroup(DimensionGroup $dimensiongroup)
    {
        $rootElement = $this->dimensiongroupElement;

        $status = $dimensiongroup->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // DimensionGroup elements and their methods
        $dimensiongroupTags = array(
            'code'                          => 'getCode',
            'name'                          => 'getName',
            'office'                        => 'getOffice',
            'shortname'                     => 'getShortName',
        );

        // Go through each DimensionGroup element and use the assigned method
        foreach ($dimensiongroupTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $dimensiongroup->$method();
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

        $dimensions = $dimensiongroup->getDimensions();

        if (!empty($dimensions)) {
            // Make dimensions element
            $dimensionsElement = $this->createElement('dimensions');
            $this->dimensiongroupElement->appendChild($dimensionsElement);

            // Element tags and their methods for dimensions
            $dimensionTags = [
                'code'      => 'getCode',
                'type'      => 'getType',
            ];

            // Go through each dimension assigned to the dimension group
            foreach ($dimensions as $dimension) {
                // Makes new dimension element
                $dimensionElement = $this->createElement('dimension');
                $dimensionsElement->appendChild($dimensionElement);

                // Go through each dimension element and use the assigned method
                foreach ($dimensionTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($dimension->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $dimensionElement->appendChild($element);
                }
            }
        }
    }
}
