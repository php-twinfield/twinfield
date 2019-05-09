<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Customer;
use PhpTwinfield\CustomerAddress;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\CustomerChildValidation;
use PhpTwinfield\CustomerCollectMandate;
use PhpTwinfield\CustomerCreditManagement;
use PhpTwinfield\CustomerFinancials;
use PhpTwinfield\CustomerLine;
use PhpTwinfield\CustomerPostingRule;
use PhpTwinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 *
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class CustomerMapper extends BaseMapper
{
    /**
     * Maps a Response object to a clean Customer entity.
     *
     * @access public
     * @param \PhpTwinfield\Response\Response $response
     * @return Customer
     * @throws \PhpTwinfield\Exception
     */
    public static function map(Response $response)
    {
        // Generate new Customer object
        $customer = new Customer();

        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Get the root/customer element
        $customerElement = $responseDOM->documentElement;

        // Set the result and status attribute
        $customer->setResult($customerElement->getAttribute('result'))
            ->setStatus(self::parseEnumAttribute('Status', $customerElement->getAttribute('status')));

        // Set the customer elements from the customer element
        $customer->setBeginPeriod(self::getField($customer, $customerElement, 'beginperiod'))
            ->setBeginYear(self::getField($customer, $customerElement, 'beginyear'))
            ->setBehaviour(self::parseEnumAttribute('Behaviour', self::getField($customer, $customerElement, 'behaviour')))
            ->setDiscountArticle(self::parseObjectAttribute('Article', $customer, $customerElement, 'discountarticle', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setCode(self::getField($customer, $customerElement, 'code'))
            ->setEndPeriod(self::getField($customer, $customerElement, 'endperiod'))
            ->setEndYear(self::getField($customer, $customerElement, 'endyear'))
            ->setGroup(self::parseObjectAttribute('DimensionGroup', $customer, $customerElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(self::parseBooleanAttribute(self::getField($customer, $customerElement, 'name')))
            ->setName(self::getField($customer, $customerElement, 'name'))
            ->setOffice(self::parseObjectAttribute('Office', $customer, $customerElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($customer, $customerElement, 'touched'))
            ->setType(self::parseObjectAttribute('DimensionType', $customer, $customerElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($customer, $customerElement, 'uid'))
            ->setWebsite(self::getField($customer, $customerElement, 'website'));

        // Set the customer elements from the customer element attributes
        $customer->setDiscountArticleID(self::getAttribute($customerElement, 'discountarticle', 'id'));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary CustomerFinancials class
            $customerFinancials = new CustomerFinancials();

            // Set the financials elements from the financials element
            $customerFinancials->setAccountType(self::parseEnumAttribute('AccountType', self::getField($customerFinancials, $financialsElement, 'accounttype')))
                ->setCollectionSchema(self::parseEnumAttribute('CollectionSchema', self::getField($customerFinancials, $financialsElement, 'collectionschema')))
                ->setDueDays(self::getField($customerFinancials, $financialsElement, 'duedays'))
                ->setEBilling(self::parseBooleanAttribute(self::getField($customerFinancials, $financialsElement, 'ebilling')))
                ->setEBillMail(self::getField($customerFinancials, $financialsElement, 'ebillmail'))
                ->setLevel(self::getField($customerFinancials, $financialsElement, 'level'))
                ->setMatchType(self::parseEnumAttribute('MatchType', self::getField($customerFinancials, $financialsElement, 'matchtype')))
                ->setMeansOfPayment(self::parseEnumAttribute('MeansOfPayment', self::getField($customerFinancials, $financialsElement, 'meansofpayment')))
                ->setPayAvailable(self::parseBooleanAttribute(self::getField($customerFinancials, $financialsElement, 'payavailable')))
                ->setPayCode(self::parseObjectAttribute('PayCode', $customerFinancials, $financialsElement, 'paycode', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setSubAnalyse(self::parseEnumAttribute('SubAnalyse', self::getField($customerFinancials, $financialsElement, 'subanalyse')))
                ->setSubstitutionLevel(self::getField($customerFinancials, $financialsElement, 'substitutionlevel'))
                ->setSubstituteWith(self::parseObjectAttribute('UnknownDimension', $customerFinancials, $financialsElement, 'substitutewith', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                ->setVatCode(self::parseObjectAttribute('VatCode', $customerFinancials, $financialsElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

            // Set the financials elements from the financials element attributes
            $customerFinancials->setPayCodeID(self::getAttribute($financialsElement, 'paycode', 'id'))
                ->setSubstituteWithID(self::getAttribute($financialsElement, 'substitutewith', 'id'))
                ->setVatCodeFixed(self::parseBooleanAttribute(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

            // Get the collectmandate element
            $collectMandateElement = $financialsElement->getElementsByTagName('collectmandate')->item(0);

            if ($collectMandateElement !== null) {
                // Make a new temporary CustomerCollectMandate class
                $customerCollectMandate = new CustomerCollectMandate();

                // Set the collect mandate elements from the collect mandate element
                $customerCollectMandate->setFirstRunDate(self::parseDateAttribute(self::getField($customerCollectMandate, $collectMandateElement, 'firstrundate')))
                    ->setID(self::getField($customerCollectMandate, $collectMandateElement, 'id'))
                    ->setSignatureDate(self::parseDateAttribute(self::getField($customerCollectMandate, $collectMandateElement, 'signaturedate')));

                // Add the collect mandate element to the customer financials class
                $customerFinancials->setCollectMandate($customerCollectMandate);

                // Clean that memory!
                unset ($customerCollectMandate);
            }

            // Get the childvalidations element
            $childValidationsDOMTag = $financialsElement->getElementsByTagName('childvalidations');

            if (isset($childValidationsDOMTag) && $childValidationsDOMTag->length > 0) {
                // Loop through each returned child validation for the customer
                foreach ($childValidationsDOMTag->item(0)->childNodes as $childValidationElement) {
                    if ($childValidationElement->nodeType !== 1) {
                        continue;
                    }

                    // Make a new temporary CustomerChildValidation class
                    $customerChildValidation = new CustomerChildValidation();

                    // Set the child validation elements from the child validation element en element attributes
                    $customerChildValidation->setLevel($childValidationElement->getAttribute('level'))
                        ->setType(self::parseEnumAttribute('GeneralLedgerType', $childValidationElement->getAttribute('type')))
                        ->setElementValue($childValidationElement->textContent);

                    // Add the child validation to the customer financials class
                    $customerFinancials->addChildValidation($customerChildValidation);

                    // Clean that memory!
                    unset ($customerChildValidation);
                }
            }

            // Set the custom class to the customer
            $customer->setFinancials($customerFinancials);
        }

        // Get the creditmanagement element
        $creditManagementElement = $responseDOM->getElementsByTagName('creditmanagement')->item(0);

        if ($creditManagementElement !== null) {
            // Make a new temporary CustomerCreditManagement class
            $customerCreditManagement = new CustomerCreditManagement();

            // Set the customer credit management elements from the creditmanagement element
            $customerCreditManagement->setBaseCreditLimit(self::parseMoneyAttribute(self::getField($customerCreditManagement, $creditManagementElement, 'basecreditlimit')))
                ->setBlocked(self::parseBooleanAttribute(self::getField($customerCreditManagement, $creditManagementElement, 'blocked')))
                ->setComment(self::getField($customerCreditManagement, $creditManagementElement, 'comment'))
                ->setFreeText1(self::parseBooleanAttribute(self::getField($customerCreditManagement, $creditManagementElement, 'freetext1')))
                ->setFreeText2(self::getField($customerCreditManagement, $creditManagementElement, 'freetext2'))
                ->setFreeText3(self::getField($customerCreditManagement, $creditManagementElement, 'freetext3'))
                ->setReminderEmail(self::getField($customerCreditManagement, $creditManagementElement, 'reminderemail'))
                ->setResponsibleUser(self::parseObjectAttribute('User', $customerCreditManagement, $creditManagementElement, 'responsibleuser', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setSendReminder(self::parseEnumAttribute('SendReminder', self::getField($customerCreditManagement, $creditManagementElement, 'sendreminder')));

            // Set the customer credit management elements from the creditmanagement element attributes
            $customerCreditManagement->setBlockedLocked(self::parseBooleanAttribute(self::getAttribute($creditManagementElement, 'blocked', 'locked')))
                ->setBlockedModified(self::parseDateTimeAttribute(self::getAttribute($creditManagementElement, 'blocked', 'modified')));

            // Set the custom class to the customer
            $customer->setCreditManagement($customerCreditManagement);
        }

        // Get the remittanceadvice element
        $remittanceAdviceElement = $responseDOM->getElementsByTagName('remittanceadvice')->item(0);

        if ($remittanceAdviceElement !== null) {
            // Set the customer elements from the remittanceadvice element
            $customer->setRemittanceAdviceSendType(self::parseEnumAttribute('RemittanceAdviceSendType', self::getField($customer, $remittanceAdviceElement, 'sendtype')))
                ->setRemittanceAdviceSendMail(self::getField($customer, $remittanceAdviceElement, 'sendmail'));
        }

        // Get the addresses element
        $addressesDOMTag = $responseDOM->getElementsByTagName('addresses');

        if (isset($addressesDOMTag) && $addressesDOMTag->length > 0) {
            // Loop through each returned address for the customer
            foreach ($addressesDOMTag->item(0)->childNodes as $addressElement) {
                if ($addressElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary CustomerAddress class
                $customerAddress = new CustomerAddress();

                // Set the default, id and type attribute
                $customerAddress->setDefault(self::parseBooleanAttribute($addressElement->getAttribute('default')))
                    ->setID($addressElement->getAttribute('id'))
                    ->setType(self::parseEnumAttribute('AddressType', $addressElement->getAttribute('type')));

                // Set the address elements from the address element
                $customerAddress->setCity(self::getField($customerAddress, $addressElement, 'city'))
                    ->setCountry(self::parseObjectAttribute('Country', $customerAddress, $addressElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setEmail(self::getField($customerAddress, $addressElement, 'email'))
                    ->setField1(self::getField($customerAddress, $addressElement, 'field1'))
                    ->setField2(self::getField($customerAddress, $addressElement, 'field2'))
                    ->setField3(self::getField($customerAddress, $addressElement, 'field3'))
                    ->setField4(self::getField($customerAddress, $addressElement, 'field4'))
                    ->setField5(self::getField($customerAddress, $addressElement, 'field5'))
                    ->setField6(self::getField($customerAddress, $addressElement, 'field6'))
                    ->setName(self::getField($customerAddress, $addressElement, 'name'))
                    ->setPostcode(self::getField($customerAddress, $addressElement, 'postcode'))
                    ->setTelephone(self::getField($customerAddress, $addressElement, 'telephone'))
                    ->setTelefax(self::getField($customerAddress, $addressElement, 'telefax'));

                // Add the address to the customer
                $customer->addAddress($customerAddress);

                // Clean that memory!
                unset ($customerAddress);
            }
        }

        // Get the banks element
        $banksDOMTag = $responseDOM->getElementsByTagName('banks');

        if (isset($banksDOMTag) && $banksDOMTag->length > 0) {
            // Loop through each returned bank for the customer
            foreach ($banksDOMTag->item(0)->childNodes as $bankElement) {
                if ($bankElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary CustomerBank class
                $customerBank = new CustomerBank();

                // Set the default and id attribute
                $customerBank->setBlocked(self::parseBooleanAttribute($bankElement->getAttribute('blocked')))
                    ->setDefault(self::parseBooleanAttribute($bankElement->getAttribute('default')))
                    ->setID($bankElement->getAttribute('id'));

                // Set the bank elements from the bank element
                $customerBank->setAscription(self::getField($customerBank, $bankElement, 'ascription'))
                    ->setAccountNumber(self::getField($customerBank, $bankElement, 'accountnumber'))
                    ->setBankName(self::getField($customerBank, $bankElement, 'bankname'))
                    ->setBicCode(self::getField($customerBank, $bankElement, 'biccode'))
                    ->setCity(self::getField($customerBank, $bankElement, 'city'))
                    ->setCountry(self::parseObjectAttribute('Country', $customerBank, $bankElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setField2(self::getField($customerBank, $bankElement, 'field2'))
                    ->setField3(self::getField($customerBank, $bankElement, 'field3'))
                    ->setIban(self::getField($customerBank, $bankElement, 'iban'))
                    ->setNatBicCode(self::getField($customerBank, $bankElement, 'natbiccode'))
                    ->setPostcode(self::getField($customerBank, $bankElement, 'postcode'))
                    ->setState(self::getField($customerBank, $bankElement, 'state'));

                // Add the bank to the customer
                $customer->addBank($customerBank);

                // Clean that memory!
                unset ($customerBank);
            }
        }

        // Get the postingrules element
        $postingrulesDOMTag = $responseDOM->getElementsByTagName('postingrules');

        if (isset($postingrulesDOMTag) && $postingrulesDOMTag->length > 0) {
            // Loop through each returned postingrule for the customer
            foreach ($postingrulesDOMTag->item(0)->childNodes as $postingruleElement) {
                if ($postingruleElement->nodeType !== 1) {
                    continue;
                }

                // Make a new temporary CustomerPostingRule class
                $customerPostingRule = new CustomerPostingRule();

                // Set the id and status attribute
                $customerPostingRule->setID($postingruleElement->getAttribute('id'))
                    ->setStatus(self::parseEnumAttribute('Status', $postingruleElement->getAttribute('status')));

                // Set the postingrule elements from the postingrule element
                $customerPostingRule->setAmount(self::parseMoneyAttribute(self::getField($customerPostingRule, $postingruleElement, 'amount')))
                    ->setCurrency(self::parseObjectAttribute('Currency', $customerPostingRule, $postingruleElement, 'currency', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setDescription(self::getField($customerPostingRule, $postingruleElement, 'description'));

                // Get the lines element
                $linesDOMTag = $postingruleElement->getElementsByTagName('lines');

                if (isset($linesDOMTag) && $linesDOMTag->length > 0) {
                    // Loop through each returned line for the posting rule
                    foreach ($linesDOMTag->item(0)->childNodes as $lineElement) {
                        if ($lineElement->nodeType !== 1) {
                            continue;
                        }

                        // Make a new temporary CustomerLine class
                        $customerLine = new CustomerLine();

                        // Set the line elements from the line element
                        $customerLine->setDescription(self::getField($customerLine, $lineElement, 'description'))
                            ->setDimension1(self::parseObjectAttribute('GeneralLedger', $customerLine, $lineElement, 'dimension1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setDimension2(self::parseObjectAttribute('CostCenter', $customerLine, $lineElement, 'dimension2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setDimension3(self::parseObjectAttribute('UnknownDimension', $customerLine, $lineElement, 'dimension3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromCode')))
                            ->setOffice(self::parseObjectAttribute('Office', $customerLine, $lineElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setRatio(self::getField($customerLine, $lineElement, 'ratio'))
                            ->setVatCode(self::parseObjectAttribute('VatCode', $customerLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

                        // Set the line elements from the line element attributes
                        $customerLine->setDimension1ID(self::getAttribute($lineElement, 'dimension1', 'id'))
                            ->setDimension2ID(self::getAttribute($lineElement, 'dimension2', 'id'))
                            ->setDimension3ID(self::getAttribute($lineElement, 'dimension2', 'id'));

                        // Add the line to the customer posting rule
                        $customerPostingRule->addLine($customerLine);

                        // Clean that memory!
                        unset ($customerLine);
                    }
                }

                // Add the postingrule to the customer
                $customer->addPostingRule($customerPostingRule);

                // Clean that memory!
                unset ($customerPostingRule);
            }
        }

        // Get the paymentconditions element
        $paymentConditionsElement = $responseDOM->getElementsByTagName('paymentconditions')->item(0);

        if ($paymentConditionsElement !== null) {
            // Set the customer elements from the paymentconditions element
            $customer->setPaymentConditionDiscountDays(self::getField($customer, $paymentConditionsElement, 'discountdays'))
                ->setPaymentConditionDiscountPercentage(self::getField($customer, $paymentConditionsElement, 'discountpercentage'));
        }

        // Return the complete object
        return $customer;
    }
}