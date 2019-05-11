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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionGroupsDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensiongroups";
    }

    /**
     * Turns a passed DimensionGroup class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the DimensionGroup to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param DimensionGroup $dimensionGroup
     * @return void | [Adds to this instance]
     */
    public function addDimensionGroup(DimensionGroup $dimensionGroup)
    {
        $dimensionGroupElement = $this->createElement('dimensiongroup');
        $this->rootElement->appendChild($dimensionGroupElement);

        $status = $dimensionGroup->getStatus();

        if (!empty($status)) {
            $dimensionGroupElement->setAttribute('status', $status);
        }

        $dimensionGroupElement->appendChild($this->createNodeWithTextContent('code', $dimensionGroup->getCode()));
        $dimensionGroupElement->appendChild($this->createNodeWithTextContent('name', $dimensionGroup->getName()));
        $dimensionGroupElement->appendChild($this->createNodeWithTextContent('office', $dimensionGroup->getOfficeToString()));
        $dimensionGroupElement->appendChild($this->createNodeWithTextContent('shortname', $dimensionGroup->getShortName()));

        $dimensions = $dimensionGroup->getDimensions();

        if (!empty($dimensions)) {
            // Make dimensions element
            $dimensionsElement = $this->createElement('dimensions');
            $dimensionGroupElement->appendChild($dimensionsElement);

            // Go through each dimension assigned to the dimension group
            foreach ($dimensions as $dimension) {
                // Makes dimension element
                $dimensionElement = $this->createElement('dimension');
                $dimensionsElement->appendChild($dimensionElement);

                $dimensionElement->appendChild($this->createNodeWithTextContent('code', $dimension->getCodeToString()));
                $dimensionElement->appendChild($this->createNodeWithTextContent('type', $dimension->getTypeToString()));
            }
        }
    }
}
