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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class RatesDocument extends \DOMDocument
{
    /**
     * Holds the <projectrate> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $rateElement;

    /**
     * Creates the <projectrate> element and adds it to the property
     * rateElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->rateElement = $this->createElement('projectrate');
        $this->appendChild($this->rateElement);
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
        $rootElement = $this->rateElement;

        $status = $rate->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // Rate elements and their methods
        $rateTags = array(
            'code'              => 'getCode',
            'currency'          => 'getCurrency',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'shortname'         => 'getShortName',
            'type'              => 'getType',
            'unit'              => 'getUnit',
        );

        // Go through each Rate element and use the assigned method
        foreach ($rateTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $rate->$method();
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

        $rateChanges = $rate->getRateChanges();

        if (!empty($rateChanges)) {
            // Make ratechanges element
            $rateChangesElement = $this->createElement('ratechanges');
            $this->rateElement->appendChild($rateChangesElement);

            // Element tags and their methods for rate changes
            $rateChangeTags = [
                'begindate'         => 'getBeginDate',
                'enddate'           => 'getEndDate',
                'externalrate'      => 'getExternalRate',
                'internalrate'      => 'getInternalRate',
            ];

            // Go through each rate change assigned to the rate
            foreach ($rateChanges as $rateChange) {
                // Makes new ratechange element
                $rateChangeElement = $this->createElement('ratechange');
                $rateChangesElement->appendChild($rateChangeElement);

                $id = $rateChange->getID();
                $lastused = $rateChange->getLastUsed();
                $status = $rateChange->getStatus();

                if (!empty($id)) {
                    $rateChangeElement->setAttribute('id', $id);
                }

                if (!empty($lastused)) {
                    $rateChangeElement->setAttribute('lastused', $lastused);
                }

                if (!empty($status)) {
                    $rateChangeElement->setAttribute('status', $status);
                }

                // Go through each ratechange element and use the assigned method
                foreach ($rateChangeTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($rateChange->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $rateChangeElement->appendChild($element);
                }
            }
        }
    }
}
