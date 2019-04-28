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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleDocument by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class AssetMethodsDocument extends \DOMDocument
{
    /**
     * Holds the <assetmethod> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $assetMethodElement;

    /**
     * Creates the <assetmethod> element and adds it to the property
     * assetMethodElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->assetMethodElement = $this->createElement('assetmethod');
        $this->appendChild($this->assetMethodElement);
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
        $rootElement = $this->assetMethodElement;

        // AssetMethod elements and their methods
        $assetMethodTags = array(
            'calcmethod'                    => 'getCalcMethod',
            'code'                          => 'getCode',
            'depreciatereconciliation'      => 'getDepreciateReconciliation',
            'name'                          => 'getName',
            'nrofperiods'                   => 'getNrOfPeriods',
            'office'                        => 'getOffice',
            'percentage'                    => 'getPercentage',
            'shortname'                     => 'getShortName',
        );

        // Go through each AssetMethod element and use the assigned method
        foreach ($assetMethodTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $assetMethod->$method();
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

        $balanceAccounts = $assetMethod->getBalanceAccounts();

        $balanceAccountsElement = $this->createElement('balanceaccounts');
        $rootElement->appendChild($balanceAccountsElement);

        // AssetMethodBalanceAccounts element and its methods
        $balanceAccountsTags = array(
            'assetstoactivate'          => 'getAssetsToActivate',
            'depreciation'              => 'getDepreciation',
            'depreciationgroup'         => 'getDepreciationGroup',
            'purchasevalue'             => 'getPurchaseValue',
            'purchasevaluegroup'        => 'getPurchaseValueGroup',
            'reconciliation'            => 'getReconciliation',
            'tobeinvoiced'              => 'getToBeInvoiced',
        );

        // Go through each AssetMethodBalanceAccounts element and use the assigned method
        foreach ($balanceAccountsTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $balanceAccounts->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $balanceAccountsElement->appendChild($element);
        }

        $profitLossAccounts = $assetMethod->getProfitLossAccounts();

        $profitLossAccountsElement = $this->createElement('profitlossaccounts');
        $rootElement->appendChild($profitLossAccountsElement);

        // AssetMethodProfitLossAccounts element and its methods
        $profitLossAccountsTags = array(
            'depreciation'              => 'getDepreciation',
            'reconciliation'            => 'getReconciliation',
            'sales'                     => 'getSales',
        );

        // Go through each AssetMethodProfitLossAccounts element and use the assigned method
        foreach ($profitLossAccountsTags as $tag => $method) {
            // Make text node for method value
            $nodeValue = $profitLossAccounts->$method();
            if (is_bool($nodeValue)) {
                $nodeValue = ($nodeValue) ? 'true' : 'false';
            }
            $node = $this->createTextNode($nodeValue);

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $profitLossAccountsElement->appendChild($element);
        }

        $freeTexts = $assetMethod->getFreeTexts();

        if (!empty($freeTexts)) {
            // Make freeTexts element
            $freeTextsElement = $this->createElement('freetexts');
            $rootElement->appendChild($freeTextsElement);

            // Go through each freetext assigned to the assetMethod
            foreach ($freeTexts as $freeText) {
                // Makes new assetMethodFreeText element
                $freeTextElement = $this->createElement('freetext');
                $freeTextsElement->appendChild($freeTextElement);

                $id = $freeText->getID();
                $type = $freeText->getType();
                $value = $freeText->getValue();

                if (!empty($id)) {
                    $freeTextElement->setAttribute('id', $id);
                }

                if (!empty($type)) {
                    $freeTextElement->setAttribute('type', $type);
                }

                if (!empty($value)) {
                    $freeTextElement->nodeValue = $value;
                }
            }
        }
    }
}
