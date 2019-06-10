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
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
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

        // Get the root/assetmethod element
        $assetmethodElement = $responseDOM->documentElement;

         // Set the inuse, result and status attribute
        $assetmethod->setInUse(self::parseBooleanAttribute($assetmethodElement->getAttribute('inuse')))
            ->setResult($assetmethodElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $assetmethodElement->getAttribute('status')));

        // Set the asset method elements from the asset method element
        $assetmethod->setCalcMethod(self::parseEnumAttribute(\PhpTwinfield\Enums\CalcMethod::class, self::getField($assetmethodElement, 'calcmethod', $assetmethod)))
            ->setCode(self::getField($assetmethodElement, 'code', $assetmethod))
            ->setCreated(self::parseDateTimeAttribute(self::getField($assetmethodElement, 'created', $assetmethod)))
            ->setDepreciateReconciliation(self::parseEnumAttribute(\PhpTwinfield\Enums\DepreciateReconciliation::class, self::getField($assetmethodElement, 'depreciatereconciliation', $assetmethod)))
            ->setModified(self::parseDateTimeAttribute(self::getField($assetmethodElement, 'modified', $assetmethod)))
            ->setName(self::getField($assetmethodElement, 'name', $assetmethod))
            ->setNrOfPeriods(self::getField($assetmethodElement, 'nrofperiods', $assetmethod))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $assetmethod, $assetmethodElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setPercentage(self::getField($assetmethodElement, 'percentage', $assetmethod))
            ->setShortName(self::getField($assetmethodElement, 'shortname', $assetmethod))
            ->setTouched(self::getField($assetmethodElement, 'touched', $assetmethod))
            ->setUser(self::parseObjectAttribute(\PhpTwinfield\User::class, $assetmethod, $assetmethodElement, 'user', array('name' => 'setName', 'shortname' => 'setShortName')));

        // Get the balanceaccounts element
        $balanceAccountsElement = $responseDOM->getElementsByTagName('balanceaccounts')->item(0);

        if ($balanceAccountsElement !== null) {
            // Make a new temporary AssetMethodBalanceAccounts class
            $assetMethodBalanceAccounts = new AssetMethodBalanceAccounts();

            // Set the asset method balance account elements from the balance accounts element
            $assetMethodBalanceAccounts
                ->setAssetsToActivate(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'assetstoactivate', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setDepreciation(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'depreciation', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setDepreciationGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'depreciationgroup', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setPurchaseValue(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'purchasevalue', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setPurchaseValueGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'purchasevaluegroup', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setReconciliation(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'reconciliation', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setToBeInvoiced(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodBalanceAccounts, $balanceAccountsElement, 'tobeinvoiced', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));

            // Set the custom class to the assetmethod
            $assetmethod->setBalanceAccounts($assetMethodBalanceAccounts);
        }

        // Get the profitlossaccounts element
        $profitLossAccountsElement = $responseDOM->getElementsByTagName('profitlossaccounts')->item(0);

        if ($profitLossAccountsElement !== null) {
            // Make a new temporary AssetMethodProfitLossAccounts class
            $assetMethodProfitLossAccounts = new AssetMethodProfitLossAccounts();

            // Set the asset method profit loss account elements from the profit loss accounts element
            $assetMethodProfitLossAccounts
                ->setDepreciation(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodProfitLossAccounts, $profitLossAccountsElement, 'depreciation', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setReconciliation(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodProfitLossAccounts, $profitLossAccountsElement, 'reconciliation', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setSales(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $assetMethodProfitLossAccounts, $profitLossAccountsElement, 'sales', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')));

            // Set the custom class to the assetmethod
            $assetmethod->setProfitLossAccounts($assetMethodProfitLossAccounts);
        }

        // Get the freetexts element
        $freetextsDOMTag = $responseDOM->getElementsByTagName('freetexts');

        if (isset($freetextsDOMTag) && $freetextsDOMTag->length > 0) {
            // Loop through each returned freetext for the assetmethod
            foreach ($freetextsDOMTag->item(0)->childNodes as $freetextElement) {
                if ($freetextElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary AssetMethodFreeText class
                $assetmethodFreeText = new AssetMethodFreeText();

                // Set the attributes (id, type, value)
                $assetmethodFreeText->setID($freetextElement->getAttribute('id'))
                    ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\FreeTextType::class, $freetextElement->getAttribute('type')))
                    ->setElementValue($freetextElement->nodeValue);

                // Add the freetext to the assetmethod
                $assetmethod->addFreeText($assetmethodFreeText);

                // Clean that memory!
                unset ($assetmethodFreeText);
            }
        }

        // Return the complete object
        return $assetmethod;
    }
}