<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\FixedAsset;

/**
 * The Document Holder for making new XML FixedAsset. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new FixedAsset.
 *
 * @package PhpTwinfield
 * @subpackage FixedAsset\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticlesDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class FixedAssetsDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $fixedAssetElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * fixedAssetElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->fixedAssetElement = $this->createElement('dimension');
        $this->appendChild($this->fixedAssetElement);
    }

    /**
     * Turns a passed FixedAsset class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the FixedAsset to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param FixedAsset $fixedAsset
     * @return void | [Adds to this instance]
     */
    public function addFixedAsset(FixedAsset $fixedAsset)
    {
        $rootElement = $this->fixedAssetElement;

        $status = $fixedAsset->getStatus();

        if (!empty($status)) {
            $rootElement->setAttribute('status', $status);
        }

        // FixedAsset elements and their methods
        $fixedAssetTags = array(
            'code'              => 'getCode',
            'name'              => 'getName',
            'office'            => 'getOffice',
            'type'              => 'getType',
        );

        // Go through each FixedAsset element and use the assigned method
        foreach ($fixedAssetTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $fixedAsset->$method();
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

        $financials = $fixedAsset->getFinancials();

        $financialsElement = $this->createElement('financials');
        $rootElement->appendChild($financialsElement);

        // FixedAssetFinancials element and its methods
        $financialsTags = array(
            'substitutionlevel'     => 'getSubstitutionLevel',
            'substitutewith'        => 'getSubstituteWith',
            'vatcode'               => 'getVatCode',
        );

        // Go through each FixedAssetFinancials element and use the assigned method
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

        $fixedAssets = $fixedAsset->getFixedAssets();

        $fixedAssetsElement = $this->createElement('fixedassets');
        $rootElement->appendChild($fixedAssetsElement);

        // DimensionTypeAddress element and its methods
        $fixedAssetsTags = array(
            'beginperiod'           => 'getBeginPeriod',
            'freetext1'             => 'getFreeText1',
            'freetext2'             => 'getFreeText2',
            'freetext3'             => 'getFreeText3',
            'freetext4'             => 'getFreeText4',
            'freetext5'             => 'getFreeText5',
            'lastdepreciation'      => 'getLastDepreciation',
            'method'                => 'getMethod',
            'nrofperiods'           => 'getNrOfPeriods',
            'percentage'            => 'getPercentage',
            'purchasedate'          => 'getPurchaseDate',
            'residualvalue'         => 'getResidualValue',
            'selldate'              => 'getSellDate',
            'stopvalue'             => 'getStopValue',
        );

        // Go through each FixedAssetFixedAssets element and use the assigned method
        foreach ($fixedAssetsTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $fixedAssets->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $fixedAssetsElement->appendChild($element);
        }

        $transactionLines = $fixedAssets->getTransactionLines();

        if (!empty($transactionLines)) {
            // Make transactionLines element
            $transactionLinesElement = $this->createElement('translines');
            $fixedAssetsElement->appendChild($transactionLinesElement);

            // Element tags and their methods for transactionLines
            $transactionLineTags = [
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

            // Go through each transactionLine assigned to the fixedAsset
            foreach ($transactionLines as $transactionLine) {
                // Makes new transactionLine element
                $transactionLineElement = $this->createElement('transline');
                $transactionLinesElement->appendChild($transactionLineElement);

                // Go through each transactionLine element and use the assigned method
                foreach ($transactionLineTags as $tag => $method) {
                    // Make the text node for the method value
                    $node = $this->createTextNode($transactionLine->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $transactionLineElement->appendChild($element);
                }
            }
        }

        $group = $fixedAsset->getGroup();

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
