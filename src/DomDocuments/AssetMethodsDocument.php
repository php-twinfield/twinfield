<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\AssetMethod;

/**
 * The Document Holder for making new XML AssetMethod. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new AssetMethod.
 *
 * @package PhpTwinfield
 * @subpackage AssetMethod\DOM
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class AssetMethodsDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "assetmethods";
    }

    /**
     * Turns a passed AssetMethod class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the AssetMethod to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param AssetMethod $assetMethod
     * @return void | [Adds to this instance]
     */
    public function addAssetMethod(AssetMethod $assetMethod)
    {
        $assetMethodElement = $this->createElement('assetmethod');
        $this->rootElement->appendChild($assetMethodElement);

        $assetMethodElement->appendChild($this->createNodeWithTextContent('calcmethod', $assetMethod->getCalcMethod()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('code', $assetMethod->getCode()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('depreciatereconciliation', $assetMethod->getDepreciateReconciliation()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('name', $assetMethod->getName()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('nrofperiods', $assetMethod->getNrOfPeriods()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('office', $assetMethod->getOfficeToCode()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('percentage', $assetMethod->getPercentage()));
        $assetMethodElement->appendChild($this->createNodeWithTextContent('shortname', $assetMethod->getShortName()));

        $balanceAccounts = $assetMethod->getBalanceAccounts();

        $balanceAccountsElement = $this->createElement('balanceaccounts');
        $assetMethodElement->appendChild($balanceAccountsElement);

        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('assetstoactivate', $balanceAccounts->getAssetsToActivateToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('depreciation', $balanceAccounts->getDepreciationToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('depreciationgroup', $balanceAccounts->getDepreciationGroupToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('purchasevalue', $balanceAccounts->getPurchaseValueToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('purchasevaluegroup', $balanceAccounts->getPurchaseValueGroupToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('reconciliation', $balanceAccounts->getReconciliationToCode()));
        $balanceAccountsElement->appendChild($this->createNodeWithTextContent('tobeinvoiced', $balanceAccounts->getToBeInvoicedToCode()));

        $profitLossAccounts = $assetMethod->getProfitLossAccounts();

        $profitLossAccountsElement = $this->createElement('profitlossaccounts');
        $assetMethodElement->appendChild($profitLossAccountsElement);

        $profitLossAccountsElement->appendChild($this->createNodeWithTextContent('depreciation', $profitLossAccounts->getDepreciationToCode()));
        $profitLossAccountsElement->appendChild($this->createNodeWithTextContent('reconciliation', $profitLossAccounts->getReconciliationToCode()));
        $profitLossAccountsElement->appendChild($this->createNodeWithTextContent('sales', $profitLossAccounts->getSalesToCode()));

        $freeTexts = $assetMethod->getFreeTexts();

        if (!empty($freeTexts)) {
            // Make freetexts element
            $freeTextsElement = $this->createElement('freetexts');
            $assetMethodElement->appendChild($freeTextsElement);

            // Go through each freetext assigned to the asset method
            foreach ($freeTexts as $freeText) {
                // Make freetext element
                $freeTextsElement->appendChild($this->createNodeWithTextContent('freetext', $freeText->getElementValue(), $freeText, array('id' => 'getID', 'type' => 'getType')));
            }
        }
    }
}
