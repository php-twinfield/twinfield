<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\FixedAsset;
use PhpTwinfield\Util;

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
        $fixedAssetElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($fixedAsset->getOffice())));
        $fixedAssetElement->appendChild($this->createNodeWithTextContent('type', Util::objectToStr($fixedAsset->getType())));

        $financials = $fixedAsset->getFinancials();

        $financialsElement = $this->createElement('financials');
        $fixedAssetElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('substitutionlevel', $financials->getSubstitutionLevel()));
        $financialsElement->appendChild($this->createNodeWithTextContent('substitutewith', Util::objectToStr($financials->getSubstituteWith())));
        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($financials->getVatCode())));

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
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('method', Util::objectToStr($fixedAssets->getMethod())));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('nrofperiods', $fixedAssets->getNrOfPeriods()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('percentage', $fixedAssets->getPercentage()));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('purchasedate', Util::formatDate($fixedAssets->getPurchaseDate())));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('residualvalue', Util::formatMoney($fixedAssets->getResidualValue())));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('selldate', Util::formatDate($fixedAssets->getSellDate())));
        $fixedAssetsElement->appendChild($this->createNodeWithTextContent('stopvalue', Util::formatMoney($fixedAssets->getStopValue())));

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

                $transactionLineElement->appendChild($this->createNodeWithTextContent('amount', Util::formatMoney($transactionLine->getAmount())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('code', Util::objectToStr($transactionLine->getCode())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim1', Util::objectToStr($transactionLine->getDim1())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim2', Util::objectToStr($transactionLine->getDim2())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim3', Util::objectToStr($transactionLine->getDim3())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim4', Util::objectToStr($transactionLine->getDim4())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim5', Util::objectToStr($transactionLine->getDim5())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('dim6', Util::objectToStr($transactionLine->getDim6())));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('line', $transactionLine->getLine()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('number', $transactionLine->getNumber()));
                $transactionLineElement->appendChild($this->createNodeWithTextContent('period', $transactionLine->getPeriod()));
            }
        }

        $group = Util::objectToStr($fixedAsset->getGroup());

        if (!empty($group)) {
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($this->createNodeWithTextContent('group', $group));
            $fixedAssetElement->appendChild($groupsElement);
        }
    }
}
