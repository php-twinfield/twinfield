<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\GeneralLedger;

/**
 * The Document Holder for making new XML GeneralLedger. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new GeneralLedger.
 *
 * @package PhpTwinfield
 * @subpackage GeneralLedger\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class GeneralLedgersDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $generalLedgerElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * generalLedgerElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->generalLedgerElement = $this->createElement('dimension');
        $this->appendChild($this->generalLedgerElement);
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
        $rootElement = $this->generalLedgerElement;

        $status = $generalLedger->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // GeneralLedger elements and their methods
        $generalLedgerTags = array(
            'beginperiod'       => 'getBeginPeriod',
            'beginyear'         => 'getBeginYear',
            'code'              => 'getCode',
            'endperiod'         => 'getEndPeriod',
            'endyear'           => 'getEndYear',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'shortname'         => 'getShortName',
            'type'              => 'getType',
        );

        // Go through each GeneralLedger element and use the assigned method
        foreach ($generalLedgerTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $generalLedger->$method();
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

        $financials = $generalLedger->getFinancials();

        $financialsElement = $this->createElement('financials');
        $rootElement->appendChild($financialsElement);

        // GeneralLedgerFinancials element and its methods
        $financialsTags = array(
            'matchtype'         => 'getMatchType',
            'accounttype'       => 'getAccountType',
            'subanalyse'        => 'getSubAnalyse',
            'vatcode'           => 'getVatCode',
        );

        // Go through each GeneralLedgerFinancials element and use the assigned method
        foreach ($financialsTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $financials->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $financialsElement->appendChild($element);
        }

        $childValidations = $financials->getChildValidations();

        if (!empty($childValidations)) {
            // Make childValidations element
            $childValidationsElement = $this->createElement('childvalidations');
            $financialsElement->appendChild($childValidationsElement);

            // Element tags and their methods for childValidations
            $childValidationTags = [
                'amount'        => 'getAmount',
                'code'          => 'getCode',
                'dim1'          => 'getDim1',
                'dim2'          => 'getDim2',
                'dim3'          => 'getDim3',
                'dim4'          => 'getDim4',
                'dim5'          => 'getDim5',
                'dim6'          => 'getDim6',
                'line'          => 'getLine',
                'number'        => 'getNumber',
                'period'        => 'getPeriod',
            ];

            // Go through each childValidation assigned to the generalLedger
            foreach ($childValidations as $childValidation) {
                // Makes new childValidation element
                $childValidationElement = $this->createElement('childvalidation');
                $childValidationsElement->appendChild($childValidationElement);

                $level = $childValidation->getLevel();
                $type = $childValidation->getType();

                if (!empty($level)) {
                    $childValidationElement->setAttribute('level', $level);
                }

                if (!empty($type)) {
                    $childValidationElement->setAttribute('type', $type);
                }

                $childValidationElement->nodeValue = $childValidation->getValue();
            }
        }

        $group = $generalLedger->getGroup();

        if (!empty($group)) {
            $groupNode = $this->createTextNode($group);
            $groupElement = $this->createElement('group');
            $groupElement->appendChild($groupNode);
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($groupElement);
            $rootElement->appendChild($groupsElement);
        }
    }
}
