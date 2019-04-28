<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\DimensionType;

/**
 * The Document Holder for making new XML DimensionType. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new DimensionType.
 *
 * @package PhpTwinfield
 * @subpackage DimensionType\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class DimensionTypesDocument extends \DOMDocument
{
    /**
     * Holds the <dimensiontype> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $dimensionTypeElement;

    /**
     * Creates the <dimensiontype> element and adds it to the property
     * dimensionTypeElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->dimensionTypeElement = $this->createElement('dimensiontype');
        $this->appendChild($this->dimensionTypeElement);
    }

    /**
     * Turns a passed DimensionType class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the DimensionType to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param DimensionType $dimensionType
     * @return void | [Adds to this instance]
     */
    public function addDimensionType(DimensionType $dimensionType)
    {
        $rootElement = $this->dimensionTypeElement;

        // DimensionType elements and their methods
        $dimensionTypeTags = array(
            'code'                          => 'getCode',
            'mask'                          => 'getMask',
            'name'                          => 'getName',
            'office'                        => 'getOffice',
            'shortname'                     => 'getShortName',
        );

        // Go through each DimensionType element and use the assigned method
        foreach ($dimensionTypeTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $dimensionType->$method();
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

        $address = $dimensionType->getAddress();

        $addressElement = $this->createElement('address');
        $rootElement->appendChild($addressElement);

        // DimensionTypeAddress element and its methods
        $addressTags = array(
            'label1'        => 'getLabel1',
            'label2'        => 'getLabel2',
            'label3'        => 'getLabel3',
            'label4'        => 'getLabel4',
            'label5'        => 'getLabel5',
            'label6'        => 'getLabel6',
        );

        // Go through each DimensionTypeAddress element and use the assigned method
        foreach ($addressTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $address->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $addressElement->appendChild($element);
        }
    }
}
