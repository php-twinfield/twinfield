<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\GeneralLedger;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML GeneralLedger. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new GeneralLedger.
 *
 * @package PhpTwinfield
 * @subpackage GeneralLedger\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class GeneralLedgersDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
    }

    /**
     * Turns a passed GeneralLedger class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the GeneralLedger to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param GeneralLedger $generalLedger
     * @return void | [Adds to this instance]
     */
    public function addGeneralLedger(GeneralLedger $generalLedger)
    {
        $generalLedgerElement = $this->createElement('dimension');
        $this->rootElement->appendChild($generalLedgerElement);

        $status = $generalLedger->getStatus();

        if (!empty($status)) {
            $generalLedgerElement->setAttribute('status', $status);
        }

        $generalLedgerElement->appendChild($this->createNodeWithTextContent('beginperiod', $generalLedger->getBeginPeriod()));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('beginyear', $generalLedger->getBeginYear()));
        
        if (!empty($generalLedger->getCode())) {
            $generalLedgerElement->appendChild($this->createNodeWithTextContent('code', $generalLedger->getCode()));
        }
        
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('endperiod', $generalLedger->getEndPeriod()));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('endyear', $generalLedger->getEndYear()));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('name', $generalLedger->getName()));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($generalLedger->getOffice())));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('shortname', $generalLedger->getShortName()));
        $generalLedgerElement->appendChild($this->createNodeWithTextContent('type', Util::objectToStr($generalLedger->getType())));

        $financials = $generalLedger->getFinancials();

        $financialsElement = $this->createElement('financials');
        $generalLedgerElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('accounttype', $financials->getAccountType()));
        $financialsElement->appendChild($this->createNodeWithTextContent('matchtype', $financials->getMatchType()));
        $financialsElement->appendChild($this->createNodeWithTextContent('subanalyse', $financials->getSubAnalyse()));
        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($financials->getVatCode())));

        $childValidations = $financials->getChildValidations();

        if (!empty($childValidations)) {
            // Make childvalidations element
            $childValidationsElement = $this->createElement('childvalidations');
            $financialsElement->appendChild($childValidationsElement);

            // Go through each childvalidation assigned to the general ledger financials
            foreach ($childValidations as $childValidation) {
                // Make childvalidation element
                $childValidationsElement->appendChild($this->createNodeWithTextContent('childvalidation', $childValidation->getElementValue(), $childValidation, array('level' => 'getLevel', 'type' => 'getType')));
            }
        }

        $group = Util::objectToStr($generalLedger->getGroup());

        if (!empty($group)) {
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($this->createNodeWithTextContent('group', $group));
            $generalLedgerElement->appendChild($groupsElement);
        }
    }
}
