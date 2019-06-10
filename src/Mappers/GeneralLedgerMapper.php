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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $generalLedgerElement->getAttribute('status')));

        // Set the general ledger elements from the general ledger element
        $generalLedger->setBeginPeriod(self::getField($generalLedgerElement, 'beginperiod', $generalLedger))
            ->setBeginYear(self::getField($generalLedgerElement, 'beginyear', $generalLedger))
            ->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($generalLedgerElement, 'behaviour', $generalLedger)))
            ->setCode(self::getField($generalLedgerElement, 'code', $generalLedger))
            ->setEndPeriod(self::getField($generalLedgerElement, 'endperiod', $generalLedger))
            ->setEndYear(self::getField($generalLedgerElement, 'endyear', $generalLedger))
            ->setGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $generalLedger, $generalLedgerElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(self::parseBooleanAttribute(self::getField($generalLedgerElement, 'inuse', $generalLedger)))
            ->setName(self::getField($generalLedgerElement, 'name', $generalLedger))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $generalLedger, $generalLedgerElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setShortName(self::getField($generalLedgerElement, 'shortname', $generalLedger))
            ->setTouched(self::getField($generalLedgerElement, 'touched', $generalLedger))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $generalLedger, $generalLedgerElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($generalLedgerElement, 'uid', $generalLedger));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary GeneralLedgerFinancials class
            $generalLedgerFinancials = new GeneralLedgerFinancials();

            // Set the financials elements from the financials element
            $generalLedgerFinancials->setAccountType(self::parseEnumAttribute(\PhpTwinfield\Enums\AccountType::class, self::getField($financialsElement, 'accounttype', $generalLedgerFinancials)))
                ->setLevel(self::getField($financialsElement, 'level', $generalLedgerFinancials))
                ->setMatchType(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchType::class, self::getField($financialsElement, 'matchtype', $generalLedgerFinancials)))
                ->setSubAnalyse(self::parseEnumAttribute(\PhpTwinfield\Enums\SubAnalyse::class, self::getField($financialsElement, 'subanalyse', $generalLedgerFinancials)))
                ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $generalLedgerFinancials, $financialsElement, 'vatcode'));

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
                    $generalLedgerChildValidation->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\ChildValidationType::class, $childValidationElement->getAttribute('type')));
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
