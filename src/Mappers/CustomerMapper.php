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
use PhpTwinfield\Secure\AuthenticatedConnection;
use PhpTwinfield\Util;

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
            ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $customerElement->getAttribute('status')));

        // Set the customer elements from the customer element
        $customer->setBeginPeriod(self::getField($customerElement, 'beginperiod', $customer))
            ->setBeginYear(self::getField($customerElement, 'beginyear', $customer))
            ->setBehaviour(self::parseEnumAttribute(\PhpTwinfield\Enums\Behaviour::class, self::getField($customerElement, 'behaviour', $customer)))
            ->setDiscountArticle(self::parseObjectAttribute(\PhpTwinfield\Article::class, $customer, $customerElement, 'discountarticle', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setCode(self::getField($customerElement, 'code', $customer))
            ->setEndPeriod(self::getField($customerElement, 'endperiod', $customer))
            ->setEndYear(self::getField($customerElement, 'endyear', $customer))
            ->setGroup(self::parseObjectAttribute(\PhpTwinfield\DimensionGroup::class, $customer, $customerElement, 'group', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setInUse(Util::parseBoolean(self::getField($customerElement, 'name', $customer)))
            ->setName(self::getField($customerElement, 'name', $customer))
            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $customer, $customerElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setTouched(self::getField($customerElement, 'touched', $customer))
            ->setType(self::parseObjectAttribute(\PhpTwinfield\DimensionType::class, $customer, $customerElement, 'type', array('name' => 'setName', 'shortname' => 'setShortName')))
            ->setUID(self::getField($customerElement, 'uid', $customer))
            ->setWebsite(self::getField($customerElement, 'website', $customer));
            
        //$currencies = self::getOfficeCurrencies($connection, $customer->getOffice());
        $currencies = ["base" => "EUR", "reporting" => "USD"];
        
        // Set the customer elements from the customer element attributes
        $customer->setDiscountArticleID(self::getAttribute($customerElement, 'discountarticle', 'id'));

        // Get the financials element
        $financialsElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialsElement !== null) {
            // Make a new temporary CustomerFinancials class
            $customerFinancials = new CustomerFinancials();

            // Set the financials elements from the financials element
            $customerFinancials->setAccountType(self::parseEnumAttribute(\PhpTwinfield\Enums\AccountType::class, self::getField($financialsElement, 'accounttype', $customerFinancials)))
                ->setCollectionSchema(self::parseEnumAttribute(\PhpTwinfield\Enums\CollectionSchema::class, self::getField($financialsElement, 'collectionschema', $customerFinancials)))
                ->setDueDays(self::getField($financialsElement, 'duedays', $customerFinancials))
                ->setEBilling(Util::parseBoolean(self::getField($financialsElement, 'ebilling', $customerFinancials)))
                ->setEBillMail(self::getField($financialsElement, 'ebillmail', $customerFinancials))
                ->setLevel(self::getField($financialsElement, 'level', $customerFinancials))
                ->setMatchType(self::parseEnumAttribute(\PhpTwinfield\Enums\MatchType::class, self::getField($financialsElement, 'matchtype', $customerFinancials)))
                ->setMeansOfPayment(self::parseEnumAttribute(\PhpTwinfield\Enums\MeansOfPayment::class, self::getField($financialsElement, 'meansofpayment', $customerFinancials)))
                ->setPayAvailable(Util::parseBoolean(self::getField($financialsElement, 'payavailable', $customerFinancials)))
                ->setPayCode(self::parseObjectAttribute(\PhpTwinfield\PayCode::class, $customerFinancials, $financialsElement, 'paycode', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setSubAnalyse(self::parseEnumAttribute(\PhpTwinfield\Enums\SubAnalyse::class, self::getField($financialsElement, 'subanalyse', $customerFinancials)))
                ->setSubstitutionLevel(self::getField($financialsElement, 'substitutionlevel', $customerFinancials))
                ->setSubstituteWith(self::parseObjectAttribute(null, $customerFinancials, $financialsElement, 'substitutewith', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $customerFinancials, $financialsElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

            // Set the financials elements from the financials element attributes
            $customerFinancials->setPayCodeID(self::getAttribute($financialsElement, 'paycode', 'id'))
                ->setSubstituteWithID(self::getAttribute($financialsElement, 'substitutewith', 'id'))
                ->setVatCodeFixed(Util::parseBoolean(self::getAttribute($financialsElement, 'vatcode', 'fixed')));

            // Get the collectmandate element
            $collectMandateElement = $financialsElement->getElementsByTagName('collectmandate')->item(0);

            if ($collectMandateElement !== null) {
                // Make a new temporary CustomerCollectMandate class
                $customerCollectMandate = new CustomerCollectMandate();

                // Set the collect mandate elements from the collect mandate element
                $customerCollectMandate->setFirstRunDate(self::parseDateAttribute(self::getField($collectMandateElement, 'firstrundate', $customerCollectMandate)))
                    ->setID(self::getField($collectMandateElement, 'id', $customerCollectMandate))
                    ->setSignatureDate(self::parseDateAttribute(self::getField($collectMandateElement, 'signaturedate', $customerCollectMandate)));

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
                        ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\ChildValidationType::class, $childValidationElement->getAttribute('type')))
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
            $customerCreditManagement->setBaseCreditLimit(self::parseMoneyAttribute(self::getField($creditManagementElement, 'basecreditlimit', $customerCreditManagement), $currencies['base']))
                ->setBlocked(Util::parseBoolean(self::getField($creditManagementElement, 'blocked', $customerCreditManagement)))
                ->setComment(self::getField($creditManagementElement, 'comment', $customerCreditManagement))
                ->setFreeText1(Util::parseBoolean(self::getField($creditManagementElement, 'freetext1', $customerCreditManagement)))
                ->setFreeText2(self::getField($creditManagementElement, 'freetext2', $customerCreditManagement))
                ->setFreeText3(self::getField($creditManagementElement, 'freetext3', $customerCreditManagement))
                ->setReminderEmail(self::getField($creditManagementElement, 'reminderemail', $customerCreditManagement))
                ->setResponsibleUser(self::parseObjectAttribute(\PhpTwinfield\User::class, $customerCreditManagement, $creditManagementElement, 'responsibleuser', array('name' => 'setName', 'shortname' => 'setShortName')))
                ->setSendReminder(self::parseEnumAttribute(\PhpTwinfield\Enums\SendReminder::class, self::getField($creditManagementElement, 'sendreminder', $customerCreditManagement)));

            // Set the customer credit management elements from the creditmanagement element attributes
            $customerCreditManagement->setBlockedLocked(Util::parseBoolean(self::getAttribute($creditManagementElement, 'blocked', 'locked')))
                ->setBlockedModified(self::parseDateTimeAttribute(self::getAttribute($creditManagementElement, 'blocked', 'modified')));

            // Set the custom class to the customer
            $customer->setCreditManagement($customerCreditManagement);
        }

        // Get the remittanceadvice element
        $remittanceAdviceElement = $responseDOM->getElementsByTagName('remittanceadvice')->item(0);

        if ($remittanceAdviceElement !== null) {
            // Set the customer elements from the remittanceadvice element
            $customer->setRemittanceAdviceSendType(self::parseEnumAttribute(\PhpTwinfield\Enums\RemittanceAdviceSendType::class, self::getField($remittanceAdviceElement, 'sendtype', $customer)))
                ->setRemittanceAdviceSendMail(self::getField($remittanceAdviceElement, 'sendmail', $customer));
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
                $customerAddress->setDefault(Util::parseBoolean($addressElement->getAttribute('default')))
                    ->setID($addressElement->getAttribute('id'))
                    ->setType(self::parseEnumAttribute(\PhpTwinfield\Enums\AddressType::class, $addressElement->getAttribute('type')));

                // Set the address elements from the address element
                $customerAddress->setCity(self::getField($addressElement, 'city', $customerAddress))
                    ->setCountry(self::parseObjectAttribute(\PhpTwinfield\Country::class, $customerAddress, $addressElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setEmail(self::getField($addressElement, 'email', $customerAddress))
                    ->setField1(self::getField($addressElement, 'field1', $customerAddress))
                    ->setField2(self::getField($addressElement, 'field2', $customerAddress))
                    ->setField3(self::getField($addressElement, 'field3', $customerAddress))
                    ->setField4(self::getField($addressElement, 'field4', $customerAddress))
                    ->setField5(self::getField($addressElement, 'field5', $customerAddress))
                    ->setField6(self::getField($addressElement, 'field6', $customerAddress))
                    ->setName(self::getField($addressElement, 'name', $customerAddress))
                    ->setPostcode(self::getField($addressElement, 'postcode', $customerAddress))
                    ->setTelephone(self::getField($addressElement, 'telephone', $customerAddress))
                    ->setTelefax(self::getField($addressElement, 'telefax', $customerAddress));

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
                $customerBank->setBlocked(Util::parseBoolean($bankElement->getAttribute('blocked')))
                    ->setDefault(Util::parseBoolean($bankElement->getAttribute('default')))
                    ->setID($bankElement->getAttribute('id'));

                // Set the bank elements from the bank element
                $customerBank->setAccountNumber(self::getField($bankElement, 'accountnumber', $customerBank))
                    ->setAddressField2(self::getField($bankElement, 'field2', $customerBank))
                    ->setAddressField3(self::getField($bankElement, 'field3', $customerBank))
                    ->setAscription(self::getField($bankElement, 'ascription', $customerBank))
                    ->setBankName(self::getField($bankElement, 'bankname', $customerBank))
                    ->setBicCode(self::getField($bankElement, 'biccode', $customerBank))
                    ->setCity(self::getField($bankElement, 'city', $customerBank))
                    ->setCountry(self::parseObjectAttribute(\PhpTwinfield\Country::class, $customerBank, $bankElement, 'country', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setIban(self::getField($bankElement, 'iban', $customerBank))
                    ->setNatBicCode(self::getField($bankElement, 'natbiccode', $customerBank))
                    ->setPostcode(self::getField($bankElement, 'postcode', $customerBank))
                    ->setState(self::getField($bankElement, 'state', $customerBank));

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
                    ->setStatus(self::parseEnumAttribute(\PhpTwinfield\Enums\Status::class, $postingruleElement->getAttribute('status')));

                // Set the postingrule elements from the postingrule element
                $customerPostingRule->setCurrency(self::parseObjectAttribute(\PhpTwinfield\Currency::class, $customerPostingRule, $postingruleElement, 'currency', array('name' => 'setName', 'shortname' => 'setShortName')))
                    ->setDescription(self::getField($postingruleElement, 'description', $customerPostingRule));
                    
                $customerPostingRule->setAmount(self::parseMoneyAttribute(self::getField($postingruleElement, 'amount', $customerPostingRule), Util::objectToStr($customerPostingRule->getCurrency())));

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
                        $customerLine->setDescription(self::getField($lineElement, 'description', $customerLine))
                            ->setDimension1(self::parseObjectAttribute(\PhpTwinfield\GeneralLedger::class, $customerLine, $lineElement, 'dimension1', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setDimension2(self::parseObjectAttribute(\PhpTwinfield\CostCenter::class, $customerLine, $lineElement, 'dimension2', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setDimension3(self::parseObjectAttribute(null, $customerLine, $lineElement, 'dimension3', array('name' => 'setName', 'shortname' => 'setShortName', 'dimensiontype' => 'setTypeFromString')))
                            ->setOffice(self::parseObjectAttribute(\PhpTwinfield\Office::class, $customerLine, $lineElement, 'office', array('name' => 'setName', 'shortname' => 'setShortName')))
                            ->setRatio(self::getField($lineElement, 'ratio', $customerLine))
                            ->setVatCode(self::parseObjectAttribute(\PhpTwinfield\VatCode::class, $customerLine, $lineElement, 'vatcode', array('name' => 'setName', 'shortname' => 'setShortName', 'type' => 'setTypeFromString')));

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
            $customer->setPaymentConditionDiscountDays(self::getField($paymentConditionsElement, 'discountdays', $customer))
                ->setPaymentConditionDiscountPercentage(self::getField($paymentConditionsElement, 'discountpercentage', $customer));
        }

        // Return the complete object
        return $customer;
    }
}