<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\FixedAsset;
use PhpTwinfield\FixedAssetFinancials;
use PhpTwinfield\FixedAssetFixedAssets;
use PhpTwinfield\FixedAssetTransactionLine;
use PhpTwinfield\Customer;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
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
        $fixedAssetElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $fixedAsset->setResult($fixedAssetElement->getAttribute('result'));
        $fixedAsset->setStatus($fixedAssetElement->getAttribute('status'));

        // FixedAsset elements and their methods
        $fixedAssetTags = array(
            'behaviour'         => 'setBehaviour',
            'code'              => 'setCode',
            'group'             => 'setGroup',
            'inuse'             => 'setInUse',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
        );

        // Loop through all the tags
        foreach ($fixedAssetTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$fixedAsset, $method]);
        }

        // Make a FixedAssetFinancials element and loop through custom tags
        $financialsTags = array(
            'accounttype'           => 'setAccountType',
            'level'                 => 'setLevel',
            'matchtype'             => 'setMatchType',
            'subanalyse'            => 'setSubAnalyse',
            'substitutionlevel'     => 'setSubstitutionLevel',
            'substitutewith'        => 'setSubstituteWith',
            'vatcode'               => 'setVatCode',
        );

        $fixedAssetFinancials = new FixedAssetFinancials();

        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Go through each fixedAssets element and add to the assigned method
            foreach ($financialsTags as $tag => $method) {
                $fixedAssetFinancials->$method(self::getField($financialsElement, $tag));
            }
        }

        // Set the custom class to the fixedAsset
        $fixedAsset->setFinancials($fixedAssetFinancials);

        // Make a FixedAssetFixedAssets element and loop through custom tags
        $fixedAssetsTags = array(
            'beginperiod'           => 'setBeginPeriod',
            'freetext1'             => 'setFreeText1',
            'freetext2'             => 'setFreeText2',
            'freetext3'             => 'setFreeText3',
            'freetext4'             => 'setFreeText4',
            'freetext5'             => 'setFreeText5',
            'lastdepreciation'      => 'setLastDepreciation',
            'method'                => 'setMethod',
            'nrofperiods'           => 'setNrOfPeriods',
            'percentage'            => 'setPercentage',
            'purchasedate'          => 'setPurchaseDate',
            'residualvalue'         => 'setResidualValue',
            'selldate'              => 'setSellDate',
            'status'                => 'setStatus',
            'stopvalue'             => 'setStopValue',
        );

        $fixedAssetFixedAssets = new FixedAssetFixedAssets();

        $fixedAssetsElement = $responseDOM->getElementsByTagName('fixedassets')->item(0);

        if ($fixedAssetsElement !== null) {
            // Go through each fixedAssets element and add to the assigned method
            foreach ($fixedAssetsTags as $tag => $method) {
                $fixedAssetFixedAssets->$method(self::getField($fixedAssetsElement, $tag));
            }

            $transactionLinesDOMTag = $fixedAssetsElement->getElementsByTagName('translines');

            if (isset($transactionLinesDOMTag) && $transactionLinesDOMTag->length > 0) {
                // Element tags and their methods for TransactionLines
                $transactionLineTags = [
                    'amount'        => 'setAmount',
                    'code'          => 'setCode',
                    'dim1'          => 'setDim1',
                    'dim2'          => 'setDim2',
                    'dim3'          => 'setDim3',
                    'dim4'          => 'setDim4',
                    'dim5'          => 'setDim5',
                    'dim6'          => 'setDim6',
                    'line'          => 'setLine',
                    'number'        => 'setNumber',
                    'period'        => 'setPeriod',
                ];

                $transactionLinesDOM = $transactionLinesDOMTag->item(0);

                // Loop through each returned transactionLine for the fixedAsset
                foreach ($transactionLinesDOM->childNodes as $transactionLineDOM) {
                    // Make a new tempory FixedAssetTransactionLine class
                    $fixedAssetTransactionLine = new FixedAssetTransactionLine();

                    // Loop through the element tags. Determine if it exists and set it if it does
                    foreach ($transactionLineTags as $tag => $method) {
                        // Get the dom element
                        $_tag = $transactionLineDOM->getElementsByTagName($tag)->item(0);

                        // Check if the tag is set, and its content is set, to prevent DOMNode errors
                        if (isset($_tag) && isset($_tag->textContent)) {
                            $fixedAssetTransactionLine->$method($_tag->textContent);
                        }
                    }

                    // Add the transactionLine to the fixedAsset
                    $fixedAssetFixedAssets->addTransactionLine($fixedAssetTransactionLine);

                    // Clean that memory!
                    unset ($fixedAssetTransactionLine);
                }
            }
        }

        // Set the custom class to the fixedAsset
        $fixedAsset->setFixedAssets($fixedAssetFixedAssets);

        return $fixedAsset;
    }
}
