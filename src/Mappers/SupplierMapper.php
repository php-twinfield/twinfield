<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\Supplier;
use PhpTwinfield\SupplierAddress;
use PhpTwinfield\SupplierBank;
use PhpTwinfield\SupplierChildValidation;
use PhpTwinfield\SupplierFinancials;
use PhpTwinfield\SupplierLine;
use PhpTwinfield\SupplierPostingRule;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class SupplierMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Supplier entity.
     *
     * @access public
     * @param \PhpTwinfield\Response\Response $response
     * @return Supplier
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Supplier object
        $supplier = new Supplier();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/supplier element
        $supplierElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $supplier->setResult($supplierElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $supplierElement->getAttribute('status')));

        // Set the supplier elements from the supplier element
        $supplier->setBeginPeriod(self::getField($supplier, $supplierElement, 'beginperiod'))
            ->setBeginYear(self::getField($supplier, $supplierElement, 'beginyear'))
            ->setBehaviour(self::parseEnumAttribute('Behaviour', self::getField($supplier, $supplierElement, 'behaviour')))
            ->setCode(self::getField($supplier, $supplierElement, 'code'))
            ->setEndPeriod(self::getField($supplier, $supplierElement, 'endperiod'))
            ->setEndYear(self::getField($supplier, $supplierElement, 'endyear'))
            ->setGroup(self::parseObjectAttribute('DimensionGroup', $supplier, $supplierElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(self::parseBooleanAttribute(self::getField($supplier, $supplierElement, 'name')))
            ->setName(self::getField($supplier, $supplierElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $supplier, $supplierElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($supplier, $supplierElement, 'touched'))
            ->setType(self::parseObjectAttribute('DimensionType', $supplier, $supplierElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($supplier, $supplierElement, 'uid'))
            ->setWebsite(self::getField($supplier, $supplierElement, 'website'));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary SupplierFinancials class
            $supplierFinancials = new SupplierFinancials();

            // Set the financials elements from the financials element
            $supplierFinancials->setAccountType(self::parseEnumAttribute('AccountType', self::getField($supplierFinancials, $financialsElement, 'accounttype')))
                ->setDueDays(self::getField($supplierFinancials, $financialsElement, 'duedays'))
                ->setLevel(self::getField($supplierFinancials, $financialsElement, 'level'))
                ->setMatchType(self::parseEnumAttribute('MatchType', self::getField($supplierFinancials, $financialsElement, 'matchtype')))
                ->setMeansOfPayment(self::parseEnumAttribute('MeansOfPayment', self::getField($supplierFinancials, $financialsElement, 'meansofpayment')))
                ->setPayAvailable(self::parseBooleanAttribute(self::getField($supplierFinancials, $financialsElement, 'payavailable')))
                ->setPayCode(self::parseObjectAttribute('PayCode', $supplierFinancials, $financialsElement, 'paycode', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setRelationsReference(self::getField($supplierFinancials, $financialsElement, 'relationsreference'))
                ->setSubAnalyse(self::parseEnumAttribute('SubAnalyse', self::getField($supplierFinancials, $financialsElement, 'subanalyse')))
                ->setSubstitutionLevel(self::getField($supplierFinancials, $financialsElement, 'substitutionlevel'))
                ->setSubstituteWith(self::parseObjectAttribute('UnknownDimension', $supplierFinancials, $financialsElement, 'substitutewith', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                ->setVatCode(self::parseObjectAttribute('VatCode', $supplierFinancials, $financialsElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

            // Set the financials elements from the financials element attributes
            $supplierFinancials->setPayCodeID(self::getAttribute($financialsElement, 'paycode', 'id'))
                ->setSubstituteWithID(self::getAttribute($financialsElement, 'substitutewith', 'id'))
                ->setVatCodeFixed(self::parseBooleanAttribute(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

            // Get the childvalidations element
            $childValidationsDOMTag = $financialsElement->getElementsByTagName('childvalidations');

            if (isset($childValidationsDOMTag) && $childValidationsDOMTag->length > 0) {
                // Loop through each returned childValidation for the supplier
                foreach ($childValidationsDOMTag->item(0)->childNodes as $childValidationElement) {
                    if ($childValidationElement->nodeType !== 1) {
                        continue;
                    }

                    // Make a new temporary SupplierChildValidation class
                    $supplierChildValidation = new SupplierChildValidation();

                    // Set the child validation elements from the child validation element en element attributes
                    $supplierChildValidation->setLevel($childValidationElement->getAttribute('level'))
                        ->setType(self::parseEnumAttribute('GeneralLedgerType', $childValidationElement->getAttribute('type')))
                        ->setElementValue($childValidationElement->textContent);

                    // Add the child validation to the supplier financials class
                    $supplierFinancials->addChildValidation($supplierChildValidation);

                    // Clean that memory!
                    unset ($supplierChildValidation);
                }
            }

            // Set the custom class to the supplier
            $supplier->setFinancials($supplierFinancials);
        }

        // Get the remittanceadvice element
        $remittanceAdviceElement = $responseDOM->getElementsByTagName('remittanceadvice')->item(0);

        if ($remittanceAdviceElement !== null) {
            // Set the supplier elements from the remittanceadvice element
            $supplier->setRemittanceAdviceSendType(self::parseEnumAttribute('RemittanceAdviceSendType', self::getField($supplier, $remittanceAdviceElement, 'sendtype')))
                ->setRemittanceAdviceSendMail(self::getField($supplier, $remittanceAdviceElement, 'sendmail'));
        }

        // Get the addresses element
        $addressesDOMTag = $responseDOM->getElementsByTagName('addresses');

        if (isset($addressesDOMTag) && $addressesDOMTag->length > 0) {
            // Loop through each returned address for the supplier
            foreach ($addressesDOMTag->item(0)->childNodes as $addressElement) {
                if ($addressElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary SupplierAddress class
                $supplierAddress = new SupplierAddress();

                // Set the default, id and type attribute
                $supplierAddress->setDefault(self::parseBooleanAttribute($addressElement->getAttribute('default')))
                    ->setID($addressElement->getAttribute('id'))
                    ->setType(self::parseEnumAttribute('AddressType', $addressElement->getAttribute('type')));

                // Set the address elements from the address element
                $supplierAddress->setCity(self::getField($supplierAddress, $addressElement, 'city'))
                    ->setCountry(self::parseObjectAttribute('Country', $supplierAddress, $addressElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setEmail(self::getField($supplierAddress, $addressElement, 'email'))
                    ->setField1(self::getField($supplierAddress, $addressElement, 'field1'))
                    ->setField2(self::getField($supplierAddress, $addressElement, 'field2'))
                    ->setField3(self::getField($supplierAddress, $addressElement, 'field3'))
                    ->setField4(self::getField($supplierAddress, $addressElement, 'field4'))
                    ->setField5(self::getField($supplierAddress, $addressElement, 'field5'))
                    ->setField6(self::getField($supplierAddress, $addressElement, 'field6'))
                    ->setName(self::getField($supplierAddress, $addressElement, 'name'))
                    ->setPostcode(self::getField($supplierAddress, $addressElement, 'postcode'))
                    ->setTelephone(self::getField($supplierAddress, $addressElement, 'telephone'))
                    ->setTelefax(self::getField($supplierAddress, $addressElement, 'telefax'));

                // Add the address to the supplier
                $supplier->addAddress($supplierAddress);

                // Clean that memory!
                unset ($supplierAddress);
            }
        }

        // Get the banks element
        $banksDOMTag = $responseDOM->getElementsByTagName('banks');

        if (isset($banksDOMTag) && $banksDOMTag->length > 0) {
            // Loop through each returned bank for the supplier
            foreach ($banksDOMTag->item(0)->childNodes as $bankElement) {
                if ($bankElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary SupplierBank class
                $supplierBank = new SupplierBank();

                // Set the default and id attribute
                $supplierBank->setBlocked(self::parseBooleanAttribute($bankElement->getAttribute('blocked')))
                    ->setDefault(self::parseBooleanAttribute($bankElement->getAttribute('default')))
                    ->setID($bankElement->getAttribute('id'));

                // Set the bank elements from the bank element
                $supplierBank->setAscription(self::getField($supplierBank, $bankElement, 'ascription'))
                    ->setAccountNumber(self::getField($supplierBank, $bankElement, 'accountnumber'))
                    ->setBankName(self::getField($supplierBank, $bankElement, 'bankname'))
                    ->setBicCode(self::getField($supplierBank, $bankElement, 'biccode'))
                    ->setCity(self::getField($supplierBank, $bankElement, 'city'))
                    ->setCountry(self::parseObjectAttribute('Country', $supplierBank, $bankElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setField2(self::getField($supplierBank, $bankElement, 'field2'))
                    ->setField3(self::getField($supplierBank, $bankElement, 'field3'))
                    ->setIban(self::getField($supplierBank, $bankElement, 'iban'))
                    ->setNatBicCode(self::getField($supplierBank, $bankElement, 'natbiccode'))
                    ->setPostcode(self::getField($supplierBank, $bankElement, 'postcode'))
                    ->setState(self::getField($supplierBank, $bankElement, 'state'));

                // Add the bank to the supplier
                $supplier->addBank($supplierBank);

                // Clean that memory!
                unset ($supplierBank);
            }
        }

        // Get the postingrules element
        $postingrulesDOMTag = $responseDOM->getElementsByTagName('postingrules');

        if (isset($postingrulesDOMTag) && $postingrulesDOMTag->length > 0) {
            // Loop through each returned postingrule for the supplier
            foreach ($postingrulesDOMTag->item(0)->childNodes as $postingruleElement) {
                if ($postingruleElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary SupplierPostingRule class
                $supplierPostingRule = new SupplierPostingRule();

                // Set the id and status attribute
                $supplierPostingRule->setID($postingruleElement->getAttribute('id'))
                    ->setStatus(self::parseEnumAttribute('Status', $postingruleElement->getAttribute('status')));

                // Set the postingrule elements from the postingrule element
                $supplierPostingRule->setAmount(self::parseMoneyAttribute(self::getField($supplierPostingRule, $postingruleElement, 'amount')))
                    ->setCurrency(self::parseObjectAttribute('Currency', $supplierPostingRule, $postingruleElement, 'currency', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setDescription(self::getField($supplierPostingRule, $postingruleElement, 'description'));

                // Get the lines element
                $linesDOMTag = $postingruleElement->getElementsByTagName('lines');

                if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
                    // Loop through each returned line for the posting rule
                    foreach ($linesDOMTag->item(0)->childNodes as $lineElement) {
                        if ($lineElement->nodeType !== 1) {
                            continue;
                        }

                        // Make a new temporary SupplierLine class
                        $supplierLine = new SupplierLine();

                        // Set the line elements from the line element
                        $supplierLine->setDescription(self::getField($supplierLine, $lineElement, 'description'))
                            ->setDimension1(self::parseObjectAttribute('GeneralLedger', $supplierLine, $lineElement, 'dimension1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setDimension2(self::parseObjectAttribute('CostCenter', $supplierLine, $lineElement, 'dimension2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setDimension3(self::parseObjectAttribute('UnknownDimension', $supplierLine, $lineElement, 'dimension3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setOffice(self::parseObjectAttribute('Office', $supplierLine, $lineElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setRatio(self::getField($supplierLine, $lineElement, 'ratio'))
                            ->setVatCode(self::parseObjectAttribute('VatCode', $supplierLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

                        // Set the line elements from the line element attributes
                        $supplierLine->setDimension1ID(self::getAttribute($lineElement, 'dimension1', 'id'))
                            ->setDimension2ID(self::getAttribute($lineElement, 'dimension2', 'id'))
                            ->setDimension3ID(self::getAttribute($lineElement, 'dimension2', 'id'));

                        // Add the line to the supplier posting rule
                        $supplierPostingRule->addLine($supplierLine);

                        // Clean that memory!
                        unset ($supplierLine);
                    }
                }

                // Add the postingrule to the supplier
                $supplier->addPostingRule($supplierPostingRule);

                // Clean that memory!
                unset ($supplierPostingRule);
            }
        }

        // Get the paymentconditions element
        $paymentConditionsElement = $responseDOM->getElementsByTagName('paymentconditions')->item(0);

        if ($paymentConditionsElement !== null) {
            // Set the supplier elements from the paymentconditions element
            $supplier->setPaymentConditionDiscountDays(self::getField($supplier, $paymentConditionsElement, 'discountdays'))
                ->setPaymentConditionDiscountPercentage(self::getField($supplier, $paymentConditionsElement, 'discountpercentage'));
        }

        // Get the blockedaccountpaymentconditions element
        $blockedAccountPaymentConditionsElement = $responseDOM->getElementsByTagName('blockedaccountpaymentconditions')->item(0);

        if ($blockedAccountPaymentConditionsElement !== null) {
            // Set the supplier elements from the blockedaccountpaymentconditions element
            $supplier->setBlockedAccountPaymentConditionsIncludeVat(self::parseEnumAttribute('BlockedAccountPaymentConditionsIncludeVat', self::getField($supplier, $blockedAccountPaymentConditionsElement, 'includevat')))
                ->setBlockedAccountPaymentConditionsPercentage(self::getField($supplier, $blockedAccountPaymentConditionsElement, 'percentage'));
        }

        // Return the complete object
        return $supplier;
    }
}