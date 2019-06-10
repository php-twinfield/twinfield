<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\FixedAsset;
use PhpTwinfield\FixedAssetFinancials;
use PhpTwinfield\FixedAssetFixedAssets;
use PhpTwinfield\FixedAssetTransactionLine;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class FixedAssetMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean FixedAsset entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return FixedAsset
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new FixedAsset object
        $fixedAsset = new FixedAsset();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        // Get the root/fixed asset element
        $fixedAssetElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $fixedAsset->setResult($fixedAssetElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $fixedAssetElement->getAttribute('status')));

        // Set the fixed asset elements from the fixed asset element
        $fixedAsset->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($fixedAssetElement, 'behaviour', $fixedAsset)))
            ->setCode(self::getField($fixedAssetElement, 'code', $fixedAsset))
            ->setGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $fixedAsset, $fixedAssetElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(self::parseBooleanAttribute(self::getField($fixedAssetElement, 'name', $fixedAsset)))
            ->setName(self::getField($fixedAssetElement, 'name', $fixedAsset))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $fixedAsset, $fixedAssetElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($fixedAssetElement, 'touched', $fixedAsset))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $fixedAsset, $fixedAssetElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($fixedAssetElement, 'uid', $fixedAsset));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary FixedAssetFinancials class
            $fixedAssetFinancials = new FixedAssetFinancials();

            // Set the financials elements from the financials element
            $fixedAssetFinancials->setAccountType(self::parseEnumAttribute(\PhpTwinfield\Enums\AccountType::class, self::getField($financialsElement, 'accounttype', $fixedAssetFinancials)))
                ->setLevel(self::getField($financialsElement, 'level', $fixedAssetFinancials))
                ->setMatchType(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchType::class, self::getField($financialsElement, 'matchtype', $fixedAssetFinancials)))
                ->setSubAnalyse(self::parseEnumAttribute(\PhpTwinfield\Enums\SubAnalyse::class, self::getField($financialsElement, 'subanalyse', $fixedAssetFinancials)))
                ->setSubstitutionLevel(self::getField($financialsElement, 'substitutionlevel', $fixedAssetFinancials))
                ->setSubstituteWith(self::parseObjectAttribute(\PhpTwinfield\CostCenter::class, $fixedAssetFinancials, $financialsElement, 'substitutewith', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $fixedAssetFinancials, $financialsElement, 'vatcode'));

            // Set the financials elements from the financials element attributes
            $fixedAssetFinancials->setSubstituteWithID(self::getAttribute($financialsElement, 'substitutewith', 'id'));
            $fixedAssetFinancials->setVatCodeFixed(self::parseBooleanAttribute(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

            // Set the custom class to the fixedAsset
            $fixedAsset->setFinancials($fixedAssetFinancials);
        }

        // Get the fixedassets element
        $fixedAssetsElement = $responseDOM->getElementsByTagName('fixedassets')->item(0);

        if ($fixedAssetsElement !== null) {
            // Make a new temporary FixedAssetFixedAssets class
            $fixedAssetFixedAssets = new FixedAssetFixedAssets();

            // Set the fixed assets elements from the fixed assets element
            $fixedAssetFixedAssets->setBeginPeriod(self::getField($fixedAssetsElement, 'beginperiod', $fixedAssetFixedAssets))
                ->setFreeText1(self::getField($fixedAssetsElement, 'freetext1', $fixedAssetFixedAssets))
                ->setFreeText2(self::getField($fixedAssetsElement, 'freetext2', $fixedAssetFixedAssets))
                ->setFreeText3(self::getField($fixedAssetsElement, 'freetext3', $fixedAssetFixedAssets))
                ->setFreeText4(self::getField($fixedAssetsElement, 'freetext4', $fixedAssetFixedAssets))
                ->setFreeText5(self::getField($fixedAssetsElement, 'freetext5', $fixedAssetFixedAssets))
                ->setLastDepreciation(self::getField($fixedAssetsElement, 'lastdepreciation', $fixedAssetFixedAssets))
                ->setMethod(self::parseObjectAttribute(\PhpTwinfield\AssetMethod::class, $fixedAssetFixedAssets, $fixedAssetsElement, 'method', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setNrOfPeriods(self::getField($fixedAssetsElement, 'nrofperiods', $fixedAssetFixedAssets))
                ->setPercentage(self::getField($fixedAssetsElement, 'percentage', $fixedAssetFixedAssets))
                ->setPurchaseDate(self::parseDateAttribute(self::getField($fixedAssetsElement, 'purchasedate', $fixedAssetFixedAssets)))
                ->setResidualValue(self::parseMoneyAttribute(self::getField($fixedAssetsElement, 'residualvalue', $fixedAssetFixedAssets)))
                ->setSellDate(self::parseDateAttribute(self::getField($fixedAssetsElement, 'selldate', $fixedAssetFixedAssets)))
                ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\FixedAssetsStatus::class, self::getField($fixedAssetsElement, 'status', $fixedAssetFixedAssets)))
                ->setStopValue(self::parseMoneyAttribute(self::getField($fixedAssetsElement, 'stopvalue', $fixedAssetFixedAssets)));

            // Set the fixed assets elements from the fixed assets element attributes
            $fixedAssetFixedAssets->setBeginPeriodLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'beginperiod', 'locked')))
                ->setMethodLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'method', 'locked')))
                ->setStatusLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'status', 'locked')))
                ->setFreeText1Locked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'freetext1', 'locked')))
                ->setFreeText2Locked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'freetext2', 'locked')))
                ->setFreeText3Locked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'freetext3', 'locked')))
                ->setFreeText4Locked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'freetext4', 'locked')))
                ->setFreeText5Locked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'freetext5', 'locked')))
                ->setLastDepreciationLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'lastdepreciation', 'locked')))
                ->setNrOfPeriodsInherited(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'nrofperiods', 'inherited')))
                ->setNrOfPeriodsLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'nrofperiods', 'locked')))
                ->setPercentageLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'percentage', 'locked')))
                ->setPurchaseDateLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'purchasedate', 'locked')))
                ->setResidualValueLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'residualvalue', 'locked')))
                ->setSellDateLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'selldate', 'locked')))
                ->setStopValueLocked(self::parseBooleanAttribute(self::getAttribute($fixedAssetsElement, 'stopvalue', 'locked')));

            // Get the translines element
            $transactionLinesDOMTag = $fixedAssetsElement->getElementsByTagName('translines');

            if (isset($transactionLinesDOMTag) && $transactionLinesDOMTag->length > 0) {
                // Loop through each returned transactionLine for the fixedAsset
                foreach ($transactionLinesDOMTag->item(0)->childNodes as $transactionLineElement) {
                    if ($transactionLineElement->nodeType !== 1) {
                        continue;
                    }

                    // Make a new temporary FixedAssetTransactionLine class
                    $fixedAssetTransactionLine = new FixedAssetTransactionLine();

                    // Set the fixed assets transaction line elements from the fixed assets transline element
                    $fixedAssetTransactionLine->setAmount(self::parseMoneyAttribute(self::getField($transactionLineElement, 'amount', $fixedAssetTransactionLine)))
                        ->setCode(self::getField($transactionLineElement, 'code', $fixedAssetTransactionLine))
                        ->setDim1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $fixedAssetTransactionLine, $transactionLineElement, 'dim1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setDim2(self::parseObjectAttribute('UnknownDimension', $fixedAssetTransactionLine, $transactionLineElement, 'dim2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setDim3(self::parseObjectAttribute('UnknownDimension', $fixedAssetTransactionLine, $transactionLineElement, 'dim3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setDim4(self::parseObjectAttribute('UnknownDimension', $fixedAssetTransactionLine, $transactionLineElement, 'dim4', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setDim5(self::parseObjectAttribute('UnknownDimension', $fixedAssetTransactionLine, $transactionLineElement, 'dim5', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setDim6(self::parseObjectAttribute('UnknownDimension', $fixedAssetTransactionLine, $transactionLineElement, 'dim6', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setType')))
                        ->setLine(self::getField($transactionLineElement, 'line', $fixedAssetTransactionLine))
                        ->setNumber(self::getField($transactionLineElement, 'number', $fixedAssetTransactionLine))
                        ->setPeriod(self::getField($transactionLineElement, 'period', $fixedAssetTransactionLine));

                    // Set the fixed assets transaction line elements from the fixed assets transline attributes
                    $fixedAssetTransactionLine->setAmountLocked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'amount', 'locked')))
                        ->setCodeLocked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'code', 'locked')))
                        ->setDim1Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim1', 'locked')))
                        ->setDim2Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim2', 'locked')))
                        ->setDim3Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim3', 'locked')))
                        ->setDim4Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim4', 'locked')))
                        ->setDim5Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim5', 'locked')))
                        ->setDim6Locked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'dim6', 'locked')))
                        ->setLineLocked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'line', 'locked')))
                        ->setNumberLocked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'number', 'locked')))
                        ->setPeriodLocked(self::parseBooleanAttribute(self::getAttribute($transactionLineElement, 'period', 'locked')));

                    // Add the transactionLine to the fixedAsset
                    $fixedAssetFixedAssets->addTransactionLine($fixedAssetTransactionLine);

                    // Clean that memory!
                    unset ($fixedAssetTransactionLine);
                }
            }

            // Set the custom class to the fixedAsset
            $fixedAsset->setFixedAssets($fixedAssetFixedAssets);
        }

        // Return the complete object
        return $fixedAsset;
    }
}
