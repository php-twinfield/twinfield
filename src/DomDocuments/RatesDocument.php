<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Rate;

/**
 * The Document Holder for making new XML Rate. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Rate.
 *
 * @package PhpTwinfield
 * @subpackage Rate\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class RatesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "projectrates";
    }

    /**
     * Turns a passed Rate class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Rate to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Rate $rate
     * @return void | [Adds to this instance]
     */
    public function addRate(Rate $rate)
    {
        $rateElement = $this->createElement('projectrate');
        $this->rootElement->appendChild($rateElement);

        $status = $rate->getStatus();

        if (!empty($status)) {
            $rateElement->setAttribute('status', $status);
        }

        $rateElement->appendChild($this->createNodeWithTextContent('code', $rate->getCode()));
        $rateElement->appendChild($this->createNodeWithTextContent('currency', $rate->getCurrencyToCode()));
        $rateElement->appendChild($this->createNodeWithTextContent('name', $rate->getName()));
        $rateElement->appendChild($this->createNodeWithTextContent('office', $rate->getOfficeToCode()));
        $rateElement->appendChild($this->createNodeWithTextContent('shortname', $rate->getShortName()));
        $rateElement->appendChild($this->createNodeWithTextContent('type', $rate->getType()));
        $rateElement->appendChild($this->createNodeWithTextContent('unit', $rate->getUnit()));

        $rateChanges = $rate->getRateChanges();

        if (!empty($rateChanges)) {
            // Make ratechanges element
            $rateChangesElement = $this->createElement('ratechanges');
            $rateElement->appendChild($rateChangesElement);

            // Go through each rate change assigned to the rate
            foreach ($rateChanges as $rateChange) {
                // Makes ratechange element
                $rateChangeElement = $this->createElement('ratechange');
                $rateChangesElement->appendChild($rateChangeElement);

                $id = $rateChange->getID();

                if (!empty($id)) {
                    $rateChangeElement->setAttribute('id', $id);
                }

                $status = $rateChange->getStatus();

                if (!empty($status)) {
                    $rateChangeElement->setAttribute('status', $status);
                }

                $rateChangeElement->appendChild($this->createNodeWithTextContent('begindate', $rateChange->getBeginDateToString()));
                $rateChangeElement->appendChild($this->createNodeWithTextContent('enddate', $rateChange->getEndDateToString()));
                $rateChangeElement->appendChild($this->createNodeWithTextContent('externalrate', $rateChange->getExternalRate()));
                $rateChangeElement->appendChild($this->createNodeWithTextContent('internalrate', $rateChange->getInternalRate()));
            }
        }
    }
}
