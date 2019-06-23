<?php
namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Customer;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML Customer. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Customer.
 *
 * @package PhpTwinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class CustomersDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
    }

    /**
     * Turns a passed Customer class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Customer to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Customer $customer
     * @return void | [Adds to this instance]
     */
    public function addCustomer(Customer $customer)
    {
        $customerElement = $this->createElement('dimension');
        $this->rootElement->appendChild($customerElement);

        $status = $customer->getStatus();

        if (!empty($status)) {
            $customerElement->setAttribute('status', $status);
        }

        $customerElement->appendChild($this->createNodeWithTextContent('beginperiod', $customer->getBeginPeriod()));
        $customerElement->appendChild($this->createNodeWithTextContent('beginyear', $customer->getBeginYear()));

        if (!empty($customer->getCode())) {
            $customerElement->appendChild($this->createNodeWithTextContent('code', $customer->getCode()));
        }

        $customerElement->appendChild($this->createNodeWithTextContent('endperiod', $customer->getEndPeriod()));
        $customerElement->appendChild($this->createNodeWithTextContent('endyear', $customer->getEndYear()));
        $customerElement->appendChild($this->createNodeWithTextContent('name', $customer->getName()));
        $customerElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($customer->getOffice())));
        $customerElement->appendChild($this->createNodeWithTextContent('shortname', $customer->getShortName()));
        $customerElement->appendChild($this->createNodeWithTextContent('type', Util::objectToStr($customer->getType())));
        $customerElement->appendChild($this->createNodeWithTextContent('website', $customer->getWebsite()));

        $financials = $customer->getFinancials();

        $financialsElement = $this->createElement('financials');
        $customerElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('collectionschema', $financials->getCollectionSchema()));
        $financialsElement->appendChild($this->createNodeWithTextContent('duedays', $financials->getDueDays()));
        $financialsElement->appendChild($this->createNodeWithTextContent('ebilling', Util::formatBoolean($financials->getEBilling())));
        $financialsElement->appendChild($this->createNodeWithTextContent('ebillmail', $financials->getEBillMail()));
        $financialsElement->appendChild($this->createNodeWithTextContent('meansofpayment', $financials->getMeansOfPayment()));
        $financialsElement->appendChild($this->createNodeWithTextContent('payavailable', Util::formatBoolean($financials->getPayAvailable())));
        $financialsElement->appendChild($this->createNodeWithTextContent('paycode', Util::objectToStr($financials->getPayCode())));
        $financialsElement->appendChild($this->createNodeWithTextContent('substitutewith', Util::objectToStr($financials->getSubstituteWith())));
        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($financials->getVatCode())));

        $collectMandate = $financials->getCollectMandate();

        if (!empty($collectMandate->getID())) {
            // Make collectmandate element
            $collectMandateElement = $this->createElement('collectmandate');
            $financialsElement->appendChild($collectMandateElement);

            $collectMandateElement->appendChild($this->createNodeWithTextContent('firstrundate', Util::formatDate($collectMandate->getFirstRunDate())));
            $collectMandateElement->appendChild($this->createNodeWithTextContent('id', $collectMandate->getID()));
            $collectMandateElement->appendChild($this->createNodeWithTextContent('signaturedate', Util::formatDate($collectMandate->getSignatureDate())));
        }

        $childValidations = $financials->getChildValidations();

        if (!empty($childValidations)) {
            // Make childvalidations element
            $childValidationsElement = $this->createElement('childvalidations');
            $financialsElement->appendChild($childValidationsElement);

            // Go through each childvalidation assigned to the customer financials
            foreach ($childValidations as $childValidation) {
                // Make childvalidation element
                $childValidationsElement->appendChild($this->createNodeWithTextContent('childvalidation', $childValidation->getElementValue(), $childValidation, array('level' => 'getLevel', 'type' => 'getType')));
            }
        }

        $creditManagement = $customer->getCreditManagement();

        // Make creditmanagement element
        $creditManagementElement = $this->createElement('creditmanagement');
        $customerElement->appendChild($creditManagementElement);

        $creditManagementElement->appendChild($this->createNodeWithTextContent('basecreditlimit', Util::formatMoney($creditManagement->getBaseCreditLimit())));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('blocked', Util::formatBoolean($creditManagement->getBlocked())));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('comment', $creditManagement->getComment()));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('freetext1', Util::formatBoolean($creditManagement->getFreeText1())));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('freetext2', $creditManagement->getFreeText2()));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('freetext3', $creditManagement->getFreeText3()));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('reminderemail', $creditManagement->getReminderEmail()));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('responsibleuser', Util::objectToStr($creditManagement->getResponsibleUser())));
        $creditManagementElement->appendChild($this->createNodeWithTextContent('sendreminder', $creditManagement->getSendReminder()));

        // Make invoicing element
        $invoicingElement = $this->createElement('invoicing');
        $customerElement->appendChild($invoicingElement);

        $invoicingElement->appendChild($this->createNodeWithTextContent('discountarticle', Util::objectToStr($customer->getDiscountArticle())));

        $addresses = $customer->getAddresses();

        if (!empty($addresses)) {
            // Make addresses element
            $addressesElement = $this->createElement('addresses');
            $customerElement->appendChild($addressesElement);

            // Go through each address assigned to the customer
            foreach ($addresses as $address) {
                // Make address element
                $addressElement = $this->createElement('address');
                $addressesElement->appendChild($addressElement);

                $default = Util::formatBoolean($address->getDefault());

                if (!empty($default)) {
                    $addressElement->setAttribute('default', $default);
                }

                $id = $address->getID();

                if (!empty($id)) {
                    $addressElement->setAttribute('id', $id);
                }

                $type = $address->getType();

                if (!empty($type)) {
                    $addressElement->setAttribute('type', $type);
                }

                $addressElement->appendChild($this->createNodeWithTextContent('city', $address->getCity()));
                $addressElement->appendChild($this->createNodeWithTextContent('country', Util::objectToStr($address->getCountry())));
                $addressElement->appendChild($this->createNodeWithTextContent('email', $address->getEmail()));
                $addressElement->appendChild($this->createNodeWithTextContent('field1', $address->getField1()));
                $addressElement->appendChild($this->createNodeWithTextContent('field2', $address->getField2()));
                $addressElement->appendChild($this->createNodeWithTextContent('field3', $address->getField3()));
                $addressElement->appendChild($this->createNodeWithTextContent('field4', $address->getField4()));
                $addressElement->appendChild($this->createNodeWithTextContent('field5', $address->getField5()));
                $addressElement->appendChild($this->createNodeWithTextContent('field6', $address->getField6()));
                $addressElement->appendChild($this->createNodeWithTextContent('name', $address->getName()));
                $addressElement->appendChild($this->createNodeWithTextContent('postcode', $address->getPostcode()));
                $addressElement->appendChild($this->createNodeWithTextContent('telefax', $address->getTelefax()));
                $addressElement->appendChild($this->createNodeWithTextContent('telephone', $address->getTelephone()));
            }
        }

        $banks = $customer->getBanks();

        if (!empty($banks)) {
            // Make banks element
            $banksElement = $this->createElement('banks');
            $customerElement->appendChild($banksElement);

            // Go through each bank assigned to the customer
            foreach ($banks as $bank) {
                // Make bank element
                $bankElement = $this->createElement('bank');
                $banksElement->appendChild($bankElement);

                $default = Util::formatBoolean($bank->getDefault());

                if (!empty($default)) {
                    $bankElement->setAttribute('default', $default);
                }

                $id = $bank->getID();

                if (!empty($id)) {
                    $bankElement->setAttribute('id', $id);
                }

                $bankAddressElement = $this->createElement('address');
                $bankAddressElement->appendChild($this->createNodeWithTextContent('field2', $bank->getAddressField2()));
                $bankAddressElement->appendChild($this->createNodeWithTextContent('field3', $bank->getAddressField3()));
                $bankElement->appendChild($bankAddressElement);

                $bankElement->appendChild($this->createNodeWithTextContent('ascription', $bank->getAscription()));
                $bankElement->appendChild($this->createNodeWithTextContent('accountnumber', $bank->getAccountNumber()));
                $bankElement->appendChild($this->createNodeWithTextContent('bankname', $bank->getBankName()));
                $bankElement->appendChild($this->createNodeWithTextContent('biccode', $bank->getBicCode()));
                $bankElement->appendChild($this->createNodeWithTextContent('city', $bank->getCity()));
                $bankElement->appendChild($this->createNodeWithTextContent('country', Util::objectToStr($bank->getCountry())));
                $bankElement->appendChild($this->createNodeWithTextContent('iban', $bank->getIban()));
                $bankElement->appendChild($this->createNodeWithTextContent('natbiccode', $bank->getNatBicCode()));
                $bankElement->appendChild($this->createNodeWithTextContent('postcode', $bank->getPostcode()));
                $bankElement->appendChild($this->createNodeWithTextContent('state', $bank->getState()));
            }
        }

        $remittanceAdviceElement = $this->createElement('remittanceadvice');
        $customerElement->appendChild($remittanceAdviceElement);

        $remittanceAdviceElement->appendChild($this->createNodeWithTextContent('sendmail', $customer->getRemittanceAdviceSendMail()));
        $remittanceAdviceElement->appendChild($this->createNodeWithTextContent('sendtype', $customer->getRemittanceAdviceSendType()));

        $group = Util::objectToStr($customer->getGroup());

        if (!empty($group)) {
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($this->createNodeWithTextContent('group', $group));
            $customerElement->appendChild($groupsElement);
        }

        $postingRules = $customer->getPostingRules();

        if (!empty($postingRules)) {
            // Make postingrules element
            $postingRulesElement = $this->createElement('postingrules');
            $customerElement->appendChild($postingRulesElement);

            // Go through each posting rule assigned to the customer
            foreach ($postingRules as $postingRule) {
                // Make postingrule element
                $postingRuleElement = $this->createElement('postingrule');
                $postingRulesElement->appendChild($postingRuleElement);

                $id = $postingRule->getID();

                if (!empty($id)) {
                    $postingRuleElement->setAttribute('id', $id);
                }

                $status = $postingRule->getStatus();

                if (!empty($status)) {
                    $postingRuleElement->setAttribute('status', $status);
                }

                $postingRuleElement->appendChild($this->createNodeWithTextContent('amount', Util::formatMoney($postingRule->getAmount())));
                $postingRuleElement->appendChild($this->createNodeWithTextContent('currency', Util::objectToStr($postingRule->getCurrency())));
                $postingRuleElement->appendChild($this->createNodeWithTextContent('description', $postingRule->getDescription()));

                $postingRuleLines = $postingRule->getLines();

                if (!empty($postingRuleLines)) {
                    // Make lines element
                    $postingRuleLinesElement = $this->createElement('lines');
                    $postingRuleElement->appendChild($postingRuleLinesElement);

                    // Go through each line assigned to the customer posting rule
                    foreach ($postingRuleLines as $postingRuleLine) {
                        // Make line element
                        $postingRuleLineElement = $this->createElement('line');
                        $postingRuleLinesElement->appendChild($postingRuleLineElement);

                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('dimension1', Util::objectToStr($postingRuleLine->getDimension1())));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('dimension2', Util::objectToStr($postingRuleLine->getDimension2())));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('dimension3', Util::objectToStr($postingRuleLine->getDimension3())));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('description', $postingRuleLine->getDescription()));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($postingRuleLine->getOffice())));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('ratio', $postingRuleLine->getRatio()));
                        $postingRuleLineElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($postingRuleLine->getVatCode())));
                    }
                }
            }
        }

        $paymentconditionsElement = $this->createElement('paymentconditions');
        $customerElement->appendChild($paymentconditionsElement);

        $paymentconditionElement = $this->createElement('paymentcondition');
        $paymentconditionsElement->appendChild($paymentconditionElement);

        $paymentconditionElement->appendChild($this->createNodeWithTextContent('discountdays', $customer->getPaymentConditionDiscountDays()));
        $paymentconditionElement->appendChild($this->createNodeWithTextContent('discountpercentage', $customer->getPaymentConditionDiscountPercentage()));
    }
}