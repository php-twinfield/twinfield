<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\GeneralLedger;
use PhpTwinfield\GeneralLedgerFinancials;
use PhpTwinfield\GeneralLedgerChildValidation;
use PhpTwinfield\Customer;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleMapper by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class GeneralLedgerMapper extends BaseMapper
{

    /**
     * Maps a Response object to a clean GeneralLedger entity.
     *
     * @access public
     *
     * @param \PhpTwinfield\Response\Response $response
     *
     * @return GeneralLedger
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new GeneralLedger object
        $generalLedger = new GeneralLedger();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();
        $generalLedgerElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $generalLedger->setResult($generalLedgerElement->getAttribute('result'));
        $generalLedger->setStatus($generalLedgerElement->getAttribute('status'));

        // GeneralLedger elements and their methods
        $generalLedgerTags = array(
            'beginperiod'       => 'setBeginPeriod',
            'beginyear'         => 'setBeginYear',
            'behaviour'         => 'setBehaviour',
            'code'              => 'setCode',
            'endperiod'         => 'setEndPeriod',
            'endyear'           => 'setEndYear',
            'group'             => 'setGroup',
            'inuse'             => 'setInUse',
            'name'              => 'setName',
            'office'            => 'setOffice',
            'shortname'         => 'setShortName',
            'touched'           => 'setTouched',
            'type'              => 'setType',
            'uid'               => 'setUID',
        );

        // Loop through all the tags
        foreach ($generalLedgerTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$generalLedger, $method]);
        }

        // Make a GeneralLedgerFinancials element and loop through custom tags
        $financialsTags = array(
            'accounttype'           => 'setAccountType',
            'level'                 => 'setLevel',
            'matchtype'             => 'setMatchType',
            'subanalyse'            => 'setSubAnalyse',
            'vatcode'               => 'setVatCode',
        );

        $generalLedgerFinancials = new GeneralLedgerFinancials();

        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Go through each generalLedgers element and add to the assigned method
            foreach ($financialsTags as $tag => $method) {
                $generalLedgerFinancials->$method(self::getField($financialsElement, $tag));
            }

            $childValidationsDOMTag = $financialsElement->getElementsByTagName('childvalidations');

            if (isset($childValidationsDOMTag) && $childValidationsDOMTag->length > 0) {
                $childValidationsDOM = $childValidationsDOMTag->item(0);

                // Loop through each returned childValidation for the generalLedger
                foreach ($childValidationsDOM->childNodes as $childValidationDOM) {
                    if ($childValidationDOM->nodeType !== 1) {
                        continue;
                    }

                    // Make a new tempory GeneralLedgerChildValidation class
                    $generalLedgerChildValidation = new GeneralLedgerChildValidation();

                    $generalLedgerChildValidation->setLevel($childValidationDOM->getAttribute('level'));
                    $generalLedgerChildValidation->setType($childValidationDOM->getAttribute('type'));
                    $generalLedgerChildValidation->setValue($childValidationDOM->textContent);

                    // Add the childValidation to the generalLedger
                    $generalLedgerFinancials->addChildValidation($generalLedgerChildValidation);

                    // Clean that memory!
                    unset ($generalLedgerChildValidation);
                }
            }
        }

        // Set the custom class to the generalLedger
        $generalLedger->setFinancials($generalLedgerFinancials);

        return $generalLedger;
    }
}
