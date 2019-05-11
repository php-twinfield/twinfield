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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatCodesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "vat";
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
        $vatCodeElement = $this->rootElement;

        $status = $vatCode->getStatus();

        if (!empty($status)) {
            $vatCodeElement->setAttribute('status', $status);
        }

        $vatCodeElement->appendChild($this->createNodeWithTextContent('code', $vatCode->getCode()));
        $vatCodeElement->appendChild($this->createNodeWithTextContent('name', $vatCode->getName()));
        $vatCodeElement->appendChild($this->createNodeWithTextContent('shortname', $vatCode->getShortName()));
        $vatCodeElement->appendChild($this->createNodeWithTextContent('type', $vatCode->getType()));

        $percentages = $vatCode->getPercentages();

        if (!empty($percentages)) {
            // Make percentages element
            $percentagesElement = $this->createElement('percentages');
            $vatCodeElement->appendChild($percentagesElement);

            // Go through each percentage assigned to the vatCode
            foreach ($percentages as $percentage) {
                // Makes percentage element
                $percentageElement = $this->createElement('percentage');
                $percentagesElement->appendChild($percentageElement);

                $percentageElement->appendChild($this->createNodeWithTextContent('date', $percentage->getDateToString()));
                $percentageElement->appendChild($this->createNodeWithTextContent('name', $percentage->getName()));
                $percentageElement->appendChild($this->createNodeWithTextContent('percentage', $percentage->getPercentage()));
                $percentageElement->appendChild($this->createNodeWithTextContent('shortname', $percentage->getShortName()));

                $accounts = $percentage->getAccounts();

                if (!empty($accounts)) {
                    // Make accounts element
                    $accountsElement = $this->createElement('accounts');
                    $percentageElement->appendChild($accountsElement);

                    // Go through each account assigned to the vat code percentage
                    foreach ($accounts as $account) {
                        // Makes account element
                        $accountElement = $this->createElement('account');
                        $accountsElement->appendChild($accountElement);

                        $id = $account->getID();

                        if (!empty($id)) {
                            $accountElement->setAttribute('id', $id);
                        }

                        $accountElement->appendChild($this->createNodeWithTextContent('dim1', $account->getDim1ToString()));
                        $accountElement->appendChild($this->createNodeWithTextContent('group', $account->getGroupToString()));
                        $accountElement->appendChild($this->createNodeWithTextContent('groupcountry', $account->getGroupCountryToString()));
                        $accountElement->appendChild($this->createNodeWithTextContent('linetype', $account->getLineType()));
                        $accountElement->appendChild($this->createNodeWithTextContent('percentage', $account->getPercentage()));
                    }
                }
            }
        }
    }
}
