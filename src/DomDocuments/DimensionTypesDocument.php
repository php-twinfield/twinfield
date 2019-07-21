<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\DimensionType;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML DimensionType. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new DimensionType.
 *
 * @package PhpTwinfield
 * @subpackage DimensionType\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class DimensionTypesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensiontypes";
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
        $dimensionTypeElement = $this->createElement('dimensiontype');
        $this->rootElement->appendChild($dimensionTypeElement);

        $dimensionTypeElement->appendChild($this->createNodeWithTextContent('code', $dimensionType->getCode()));
        $dimensionTypeElement->appendChild($this->createNodeWithTextContent('mask', $dimensionType->getMask()));
        $dimensionTypeElement->appendChild($this->createNodeWithTextContent('name', $dimensionType->getName()));
        $dimensionTypeElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($dimensionType->getOffice())));
        $dimensionTypeElement->appendChild($this->createNodeWithTextContent('shortname', $dimensionType->getShortName()));

        $address = $dimensionType->getAddress();

        $addressElement = $this->createElement('address');
        $dimensionTypeElement->appendChild($addressElement);

        $addressElement->appendChild($this->createNodeWithTextContent('label1', $address->getLabel1()));
        $addressElement->appendChild($this->createNodeWithTextContent('label2', $address->getLabel2()));
        $addressElement->appendChild($this->createNodeWithTextContent('label3', $address->getLabel3()));
        $addressElement->appendChild($this->createNodeWithTextContent('label4', $address->getLabel4()));
        $addressElement->appendChild($this->createNodeWithTextContent('label5', $address->getLabel5()));
        $addressElement->appendChild($this->createNodeWithTextContent('label6', $address->getLabel6()));

        $levels = $dimensionType->getLevels();

        $levelsElement = $this->createElement('levels');
        $dimensionTypeElement->appendChild($levelsElement);

        $levelsElement->appendChild($this->createNodeWithTextContent('financials', $levels->getFinancials()));
        $levelsElement->appendChild($this->createNodeWithTextContent('time', $levels->getTime()));
    }
}
