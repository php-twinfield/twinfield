<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\GeneralLedger;
use PhpTwinfield\GeneralLedgerChildValidation;
use PhpTwinfield\GeneralLedgerFinancials;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
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

        // Get the root/dimension element
        $generalLedgerElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $generalLedger->setResult($generalLedgerElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $generalLedgerElement->getAttribute('status')));

        // Set the general ledger elements from the general ledger element
        $generalLedger->setBeginPeriod(self::getField($generalLedger, $generalLedgerElement, 'beginperiod'))
            ->setBeginYear(self::getField($generalLedger, $generalLedgerElement, 'beginyear'))
            ->setBehaviour(self::parseEnumAttribute('Behaviour', self::getField($generalLedger, $generalLedgerElement, 'behaviour')))
            ->setCode(self::getField($generalLedger, $generalLedgerElement, 'code'))
            ->setEndPeriod(self::getField($generalLedger, $generalLedgerElement, 'endperiod'))
            ->setEndYear(self::getField($generalLedger, $generalLedgerElement, 'endyear'))
            ->setGroup(self::parseObjectAttribute('DimensionGroup', $generalLedger, $generalLedgerElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(self::parseBooleanAttribute(self::getField($generalLedger, $generalLedgerElement, 'inuse')))
            ->setName(self::getField($generalLedger, $generalLedgerElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $generalLedger, $generalLedgerElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($generalLedger, $generalLedgerElement, 'shortname'))
            ->setTouched(self::getField($generalLedger, $generalLedgerElement, 'touched'))
            ->setType(self::parseObjectAttribute('DimensionType', $generalLedger, $generalLedgerElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($generalLedger, $generalLedgerElement, 'uid'));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary GeneralLedgerFinancials class
            $generalLedgerFinancials = new GeneralLedgerFinancials();

            // Set the financials elements from the financials element
            $generalLedgerFinancials->setAccountType(self::parseEnumAttribute('AccountType', self::getField($generalLedgerFinancials, $financialsElement, 'accounttype')))
                ->setLevel(self::getField($generalLedgerFinancials, $financialsElement, 'level'))
                ->setMatchType(self::parseEnumAttribute('MatchType', self::getField($generalLedgerFinancials, $financialsElement, 'matchtype')))
                ->setSubAnalyse(self::parseEnumAttribute('SubAnalyse', self::getField($generalLedgerFinancials, $financialsElement, 'subanalyse')))
                ->setVatCode(self::parseObjectAttribute('VatCode', $generalLedgerFinancials, $financialsElement, 'vatcode'));

            // Set the financials elements from the financials element attributes
            $generalLedgerFinancials->setVatCodeFixed(self::parseBooleanAttribute(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

            // Get the childvalidations element
            $childValidationsDOMTag = $financialsElement->getElementsByTagName('childvalidations');

            if (isset($childValidationsDOMTag) && $childValidationsDOMTag->length > 0) {
                // Loop through each returned childValidation for the generalLedger
                foreach ($childValidationsDOMTag->item(0)->childNodes as $childValidationElement) {
                    if ($childValidationElement->nodeType !== 1) {
                        continue;
                    }

                    // Make a new temporary GeneralLedgerChildValidation class
                    $generalLedgerChildValidation = new GeneralLedgerChildValidation();

                    // Set the child validation elements from the child validation element en element attributes
                    $generalLedgerChildValidation->setLevel($childValidationElement->getAttribute('level'));
                    $generalLedgerChildValidation->setType(self::parseEnumAttribute('ChildValidationType', $childValidationElement->getAttribute('type')));
                    $generalLedgerChildValidation->setElementValue($childValidationElement->textContent);

                    // Add the child validation to the general ledger financials class
                    $generalLedgerFinancials->addChildValidation($generalLedgerChildValidation);

                    // Clean that memory!
                    unset ($generalLedgerChildValidation);
                }
            }

            // Set the custom class to the general ledger
            $generalLedger->setFinancials($generalLedgerFinancials);
        }

        // Return the complete object
        return $generalLedger;
    }
}
