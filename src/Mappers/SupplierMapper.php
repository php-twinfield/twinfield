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
use PhpTwinfield\Util;

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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $supplierElement->getAttribute('status')));

        // Set the supplier elements from the supplier element
        $supplier->setBeginPeriod(self::getField($supplierElement, 'beginperiod', $supplier))
            ->setBeginYear(self::getField($supplierElement, 'beginyear', $supplier))
            ->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($supplierElement, 'behaviour', $supplier)))
            ->setCode(self::getField($supplierElement, 'code', $supplier))
            ->setEndPeriod(self::getField($supplierElement, 'endperiod', $supplier))
            ->setEndYear(self::getField($supplierElement, 'endyear', $supplier))
            ->setGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $supplier, $supplierElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(Util::parseBoolean(self::getField($supplierElement, 'name', $supplier)))
            ->setName(self::getField($supplierElement, 'name', $supplier))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $supplier, $supplierElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($supplierElement, 'touched', $supplier))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $supplier, $supplierElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($supplierElement, 'uid', $supplier))
            ->setWebsite(self::getField($supplierElement, 'website', $supplier));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary SupplierFinancials class
            $supplierFinancials = new SupplierFinancials();

            // Set the financials elements from the financials element
            $supplierFinancials->setAccountType(self::parseEnumAttribute(\PhpTwinfield\Enums\AccountType::class, self::getField($financialsElement, 'accounttype', $supplierFinancials)))
                ->setDueDays(self::getField($financialsElement, 'duedays', $supplierFinancials))
                ->setLevel(self::getField($financialsElement, 'level', $supplierFinancials))
                ->setMatchType(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchType::class, self::getField($financialsElement, 'matchtype', $supplierFinancials)))
                ->setMeansOfPayment(self::parseEnumAttribute(\PhpTwinfield\Enums\MeansOfPayment::class, self::getField($financialsElement, 'meansofpayment', $supplierFinancials)))
                ->setPayAvailable(Util::parseBoolean(self::getField($financialsElement, 'payavailable', $supplierFinancials)))
                ->setPayCode(self::parseObjectAttribute(\PhpTwinfield\PayCode::class, $supplierFinancials, $financialsElement, 'paycode', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setRelationsReference(self::getField($financialsElement, 'relationsreference', $supplierFinancials))
                ->setSubAnalyse(self::parseEnumAttribute(\PhpTwinfield\Enums\SubAnalyse::class, self::getField($financialsElement, 'subanalyse', $supplierFinancials)))
                ->setSubstitutionLevel(self::getField($financialsElement, 'substitutionlevel', $supplierFinancials))
                ->setSubstituteWith(self::parseObjectAttribute('UnknownDimension', $supplierFinancials, $financialsElement, 'substitutewith', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $supplierFinancials, $financialsElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

            // Set the financials elements from the financials element attributes
            $supplierFinancials->setPayCodeID(self::getAttribute($financialsElement, 'paycode', 'id'))
                ->setSubstituteWithID(self::getAttribute($financialsElement, 'substitutewith', 'id'))
                ->setVatCodeFixed(Util::parseBoolean(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

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
                        ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\ChildValidationType::class, $childValidationElement->getAttribute('type')))
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
            $supplier->setRemittanceAdviceSendType(self::parseEnumAttribute(\PhpTwinfield\Enums\RemittanceAdviceSendType::class, self::getField($remittanceAdviceElement, 'sendtype', $supplier)))
                ->setRemittanceAdviceSendMail(self::getField($remittanceAdviceElement, 'sendmail', $supplier));
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
                $supplierAddress->setDefault(Util::parseBoolean($addressElement->getAttribute('default')))
                    ->setID($addressElement->getAttribute('id'))
                    ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\AddressType::class, $addressElement->getAttribute('type')));

                // Set the address elements from the address element
                $supplierAddress->setCity(self::getField($addressElement, 'city', $supplierAddress))
                    ->setCountry(self::parseObjectAttribute(\PhpTwinfield\Country::class, $supplierAddress, $addressElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setEmail(self::getField($addressElement, 'email', $supplierAddress))
                    ->setField1(self::getField($addressElement, 'field1', $supplierAddress))
                    ->setField2(self::getField($addressElement, 'field2', $supplierAddress))
                    ->setField3(self::getField($addressElement, 'field3', $supplierAddress))
                    ->setField4(self::getField($addressElement, 'field4', $supplierAddress))
                    ->setField5(self::getField($addressElement, 'field5', $supplierAddress))
                    ->setField6(self::getField($addressElement, 'field6', $supplierAddress))
                    ->setName(self::getField($addressElement, 'name', $supplierAddress))
                    ->setPostcode(self::getField($addressElement, 'postcode', $supplierAddress))
                    ->setTelephone(self::getField($addressElement, 'telephone', $supplierAddress))
                    ->setTelefax(self::getField($addressElement, 'telefax', $supplierAddress));

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
                $supplierBank->setBlocked(Util::parseBoolean($bankElement->getAttribute('blocked')))
                    ->setDefault(Util::parseBoolean($bankElement->getAttribute('default')))
                    ->setID($bankElement->getAttribute('id'));

                // Set the bank elements from the bank element
                $supplierBank->setAccountNumber(self::getField($bankElement, 'accountnumber', $supplierBank))
                    ->setAddressField2(self::getField($bankElement, 'field2', $supplierBank))
                    ->setAddressField3(self::getField($bankElement, 'field3', $supplierBank))
                    ->setAscription(self::getField($bankElement, 'ascription', $supplierBank))
                    ->setBankName(self::getField($bankElement, 'bankname', $supplierBank))
                    ->setBicCode(self::getField($bankElement, 'biccode', $supplierBank))
                    ->setCity(self::getField($bankElement, 'city', $supplierBank))
                    ->setCountry(self::parseObjectAttribute(\PhpTwinfield\Country::class, $supplierBank, $bankElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setIban(self::getField($bankElement, 'iban', $supplierBank))
                    ->setNatBicCode(self::getField($bankElement, 'natbiccode', $supplierBank))
                    ->setPostcode(self::getField($bankElement, 'postcode', $supplierBank))
                    ->setState(self::getField($bankElement, 'state', $supplierBank));

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
                    ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $postingruleElement->getAttribute('status')));

                // Set the postingrule elements from the postingrule element
                $supplierPostingRule->setAmount(self::parseMoneyAttribute(self::getField($postingruleElement, 'amount', $supplierPostingRule)))
                    ->setCurrency(self::parseObjectAttribute(\PhpTwinfield\Currency::class, $supplierPostingRule, $postingruleElement, 'currency', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setDescription(self::getField($postingruleElement, 'description', $supplierPostingRule));

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
                        $supplierLine->setDescription(self::getField($lineElement, 'description', $supplierLine))
                            ->setDimension1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $supplierLine, $lineElement, 'dimension1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setDimension2(self::parseObjectAttribute(\PhpTwinfield\CostCenter::class, $supplierLine, $lineElement, 'dimension2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setDimension3(self::parseObjectAttribute('UnknownDimension', $supplierLine, $lineElement, 'dimension3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $supplierLine, $lineElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setRatio(self::getField($lineElement, 'ratio', $supplierLine))
                            ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $supplierLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

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
            $supplier->setPaymentConditionDiscountDays(self::getField($paymentConditionsElement, 'discountdays', $supplier))
                ->setPaymentConditionDiscountPercentage(self::getField($paymentConditionsElement, 'discountpercentage', $supplier));
        }

        // Get the blockedaccountpaymentconditions element
        $blockedAccountPaymentConditionsElement = $responseDOM->getElementsByTagName('blockedaccountpaymentconditions')->item(0);

        if ($blockedAccountPaymentConditionsElement !== null) {
            // Set the supplier elements from the blockedaccountpaymentconditions element
            $supplier->setBlockedAccountPaymentConditionsIncludeVat(self::parseEnumAttribute(\PhpTwinfield\Enums\BlockedAccountPaymentConditionsIncludeVat::class, self::getField($blockedAccountPaymentConditionsElement, 'includevat', $supplier)))
                ->setBlockedAccountPaymentConditionsPercentage(self::getField($blockedAccountPaymentConditionsElement, 'percentage', $supplier));
        }

        // Return the complete object
        return $supplier;
    }
}