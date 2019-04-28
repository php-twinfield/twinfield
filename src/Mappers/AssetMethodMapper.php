<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\AssetMethod;
use PhpTwinfield\AssetMethodBalanceAccounts;
use PhpTwinfield\AssetMethodFreeText;
use PhpTwinfield\AssetMethodProfitLossAccounts;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class AssetMethodMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean AssetMethod entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return AssetMethod
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new AssetMethod object
        $assetmethod = new AssetMethod();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $assetmethodElement = $responseDOM->documentElement;

         // Set the inuse, result and status attribute
        $assetmethod->setInUse($assetmethodElement->getAttribute('inuse'))
            ->setResult($assetmethodElement->getAttribute('result'))
            ->setStatus($assetmethodElement->getAttribute('status'));

        // AssetMethod elements and their methods
        $assetmethodTags = array(
            'calcmethod'                    => 'setCalcMethod',
            'code'                          => 'setCode',
            'created'                       => 'setCreated',
            'depreciatereconciliation'      => 'setDepreciateReconciliation',
            'modified'                      => 'setModified',
            'name'                          => 'setName',
            'nrofperiods'                   => 'setNrOfPeriods',
            'office'                        => 'setOffice',
            'percentage'                    => 'setPercentage',
            'shortname'                     => 'setShortName',
            'touched'                       => 'setTouched',
            'user'                          => 'setUser',
        );

        // Loop through all the tags
        foreach ($assetmethodTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$assetmethod, $method]);
        }

        // Make an AssetMethodBalanceAccount element and loop through custom tags
        $balanceAccountsTags = array(
            'assetstoactivate'          => 'setAssetsToActivate',
            'depreciation'              => 'setDepreciation',
            'depreciationgroup'         => 'setDepreciationGroup',
            'purchasevaluegroup'        => 'setPurchaseValueGroup',
            'purchasevalue'             => 'setPurchaseValue',
            'reconciliation'            => 'setReconciliation',
            'tobeinvoiced'              => 'setToBeInvoiced',
        );

        $assetMethodBalanceAccounts = new AssetMethodBalanceAccounts();

        $balanceAccountsElement = $responseDOM->getElementsByTagName('balanceaccounts')->item(0);

        if ($balanceAccountsElement !== null) {
            // Go through each balanceaccounts element and add to the assigned method
            foreach ($balanceAccountsTags as $tag => $method) {
                $assetMethodBalanceAccounts->$method(self::getField($balanceAccountsElement, $tag));
            }
        }

        // Set the custom class to the assetmethod
        $assetmethod->setBalanceAccounts($assetMethodBalanceAccounts);

        // Make an AssetMethodProfitLossAccount element and loop through custom tags
        $profitLossAccountsTags = array(
            'depreciation'          => 'setDepreciation',
            'reconciliation'        => 'setReconciliation',
            'sales'                 => 'setSales',
        );

        $assetMethodProfitLossAccounts = new AssetMethodProfitLossAccounts();

        $profitLossAccountsElement = $responseDOM->getElementsByTagName('profitlossaccounts')->item(0);

        if ($profitLossAccountsElement !== null) {
            // Go through each profitlossaccounts element and add to the assigned method
            foreach ($profitLossAccountsTags as $tag => $method) {
                $assetMethodProfitLossAccounts->$method(self::getField($profitLossAccountsElement, $tag));
            }
        }

        // Set the custom class to the assetmethod
        $assetmethod->setProfitLossAccounts($assetMethodProfitLossAccounts);

        $freetextsDOMTag = $responseDOM->getElementsByTagName('freetexts');

        if (isset($freetextsDOMTag) && $freetextsDOMTag->length > 0) {
            $freetextsDOM = $freetextsDOMTag->item(0);

            // Loop through each returned freetext for the assetmethod
            foreach ($freetextsDOM->childNodes as $freetextDOM) {
                if ($freetextDOM->nodeType !== 1) {
                    continue;
                }

                // Make a new tempory AssetMethodFreeText class
                $assetmethodFreeText = new AssetMethodFreeText();

                // Set the attributes (id, type, value)
                $assetmethodFreeText->setID($freetextDOM->getAttribute('id'))
                    ->setType($freetextDOM->getAttribute('type'))
                    ->setValue($freetextDOM->nodeValue);

                // Add the freetext to the assetmethod
                $assetmethod->addFreeText($assetmethodFreeText);

                // Clean that memory!
                unset ($assetmethodFreeText);
            }
        }

        return $assetmethod;
    }
}
