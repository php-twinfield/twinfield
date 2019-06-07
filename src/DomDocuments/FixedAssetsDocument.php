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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class FixedAssetsDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
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
        $fixedAssetElement = $this->createElement('dimension');
        $this->rootElement->appendChild($fixedAssetElement);

        $status = $fixedAsset->getStatus();

        if (!empty($status)) {
            $fixedAssetElement->setAttribute('status', $status);
        }

        if (!empty($fixedAsset->getCode())) {
            $fixedAssetElement->appendChild($this->createNodeWithTextContent('code', $fixedAsset->getCode()));
        }

        $fixedAssetElement->appendChild($this->createNodeWithTextContent('name', $fixedAsset->getName()));
        $fixedAssetElement->appendChild($this->createNodeWithTextContent('office', $fixedAsset->getOfficeToString()));
        $fixedAssetElement->appendChild($this->createNodeWithTextContent('type', $fixedAsset->getTypeToString()));

        $financials = $fixedAsset->getFinancials();

        $financialsElement = $this->createElement('financials');
        $fixedAssetElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('substitutionlevel', $financials->getSubstitutionLevel()));
        $financialsElement->appendChild($this->createNodeWithTextContent('substitutewith', $financials->getSubstituteWithToString()));
        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', $financials->getVatCodeToString()));

        $fixedAssets = $fixedAsset->getFixedAssets();

        $fixedAssetsElement = $this->createElement('fixedassets');
        $fixedAssetElement->appendChild($fixedAssetsElement);

        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('beginperiod', $fixedAssets->getBeginPeriod()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('freetext1', $fixedAssets->getFreeText1()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('freetext2', $fixedAssets->getFreeText2()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('freetext3', $fixedAssets->getFreeText3()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('freetext4', $fixedAssets->getFreeText4()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('freetext5', $fixedAssets->getFreeText5()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('lastdepreciation', $fixedAssets->getLastDepreciation()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('method', $fixedAssets->getMethodToString()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('nrofperiods', $fixedAssets->getNrOfPeriods()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('percentage', $fixedAssets->getPercentage()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('purchasedate', $fixedAssets->getPurchaseDateToString()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('residualvalue', $fixedAssets->getResidualValueToFloat()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('selldate', $fixedAssets->getSellDateToString()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('stopvalue', $fixedAssets->getStopValueToFloat()));

        $transactionLines = $fixedAssets->getTransactionLines();

        if (!empty($transactionLines)) {
            // Make translines element
            $transactionLinesElement = $this->createElement('translines');
            $fixedAssetsElement->appendChild($transactionLinesElement);

            // Go through each transaction line assigned to the fixed asset fixed assets
            foreach ($transactionLines as $transactionLine) {
                // Make transline element
                $transactionLineElement = $this->createElement('transline');
                $transactionLinesElement->appendChild($transactionLineElement);

                $transactionLineElement->appendChild($this->createNodeWithTextContent('amount', $transactionLine->getAmountToFloat()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('code', $transactionLine->getCodeToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim1', $transactionLine->getDim1ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim2', $transactionLine->getDim2ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim3', $transactionLine->getDim3ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim4', $transactionLine->getDim4ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim5', $transactionLine->getDim5ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim6', $transactionLine->getDim6ToString()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('line', $transactionLine->getLine()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('number', $transactionLine->getNumber()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('period', $transactionLine->getPeriod()));
            }
        }

        $group = $fixedAsset->getGroupToString();

        if (!empty($group)) {
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($this->createNodeWithTextContent('group', $group));
            $fixedAssetElement->appendChild($groupsElement);
        }
    }
}
