<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\VatCode;

/**
 * The Document Holder for making new XML VatCode. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new VatCode.
 *
 * @package PhpTwinfield
 * @subpackage VatCode\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class VatCodesDocument extends \DOMDocument
{
    /**
     * Holds the <vat> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $vatCodeElement;

    /**
     * Creates the <vat> element and adds it to the property
     * vatCodeElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->vatCodeElement = $this->createElement('vat');
        $this->appendChild($this->vatCodeElement);
    }

    /**
     * Turns a passed VatCode class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the VatCode to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param VatCode $vatCode
     * @return void | [Adds to this instance]
     */
    public function addVatCode(VatCode $vatCode)
    {
        $rootElement = $this->vatCodeElement;

        $status = $vatCode->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // VatCode elements and their methods
        $vatCodeTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'shortname'         => 'getShortName',
            'type'              => 'getType',
        );

        // Go through each VatCode element and use the assigned method
        foreach ($vatCodeTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $vatCode->$method();
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

        $percentages = $vatCode->getPercentages();

        if (!empty($percentages)) {
            // Make percentages element
            $percentagesElement = $this->createElement('percentages');
            $this->vatCodeElement->appendChild($percentagesElement);

            // Element tags and their methods for percentages
            $percentageTags = [
                'date'            => 'getDate',
                'name'            => 'getName',
                'percentage'      => 'getPercentage',
                'shortname'       => 'getShortName',
            ];

            // Go through each percentage assigned to the vatCode
            foreach ($percentages as $percentage) {
                // Makes new VatCodePercentage element
                $percentageElement = $this->createElement('percentage');
                $percentagesElement->appendChild($percentageElement);

                // Go through each percentage element and use the assigned method
                foreach ($percentageTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($percentage->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $percentageElement->appendChild($element);
                }

                $accounts = $percentage->getAccounts();

                if (!empty($accounts)) {
                    // Make addresses element
                    $accountsElement = $this->createElement('accounts');
                    $percentageElement->appendChild($accountsElement);

                    // Element tags and their methods for accounts
                    $accountTags = [
                        'dim1'              => 'getDim1',
                        'group'             => 'getGroup',
                        'groupcountry'      => 'getGroupCountry',
                        'linetype'          => 'getLineType',
                        'percentage'        => 'getPercentage',
                    ];

                    // Go through each account assigned to the percentage
                    foreach ($accounts as $account) {
                        // Makes new VatCodeAccount element
                        $accountElement = $this->createElement('account');
                        $accountsElement->appendChild($accountElement);

                        $id = $account->getID();

                        if (!empty($id)) {
                            $accountElement->setAttribute('id', $id);
                        }

                        // Go through each account element and use the assigned method
                        foreach ($accountTags as $tag => $method) {
                            // Make the text node for the method value
                            $node = $this->createTextNode($account->$method());

                            // Make the actual element and assign the text node
                            $element = $this->createElement($tag);
                            $element->appendChild($node);

                            // Add the completed element
                            $accountElement->appendChild($element);
                        }
                    }
                }
            }
        }
    }
}
