<?php
namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Supplier;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML Supplier. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Supplier.
 *
 * @package PhpTwinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class SuppliersDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "dimensions";
    }

    /**
     * Turns a passed Supplier class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Supplier to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Supplier $supplier
     * @return void | [Adds to this instance]
     */
    public function addSupplier(Supplier $supplier)
    {
        $supplierElement = $this->createElement('dimension');
        $this->rootElement->appendChild($supplierElement);

        $status = $supplier->getStatus();

        if (!empty($status)) {
            $supplierElement->setAttribute('status', $status);
        }

        $supplierElement->appendChild($this->createNodeWithTextContent('beginperiod', $supplier->getBeginPeriod()));
        $supplierElement->appendChild($this->createNodeWithTextContent('beginyear', $supplier->getBeginYear()));

        if (!empty($supplier->getCode())) {
            $supplierElement->appendChild($this->createNodeWithTextContent('code', $supplier->getCode()));
        }

        $supplierElement->appendChild($this->createNodeWithTextContent('endperiod', $supplier->getEndPeriod()));
        $supplierElement->appendChild($this->createNodeWithTextContent('endyear', $supplier->getEndYear()));
        $supplierElement->appendChild($this->createNodeWithTextContent('name', $supplier->getName()));
        $supplierElement->appendChild($this->createNodeWithTextContent('office', Util::objectToStr($supplier->getOffice())));
        $supplierElement->appendChild($this->createNodeWithTextContent('shortname', $supplier->getShortName()));
        $supplierElement->appendChild($this->createNodeWithTextContent('type', Util::objectToStr($supplier->getType())));
        $supplierElement->appendChild($this->createNodeWithTextContent('website', $supplier->getWebsite()));

        $financials = $supplier->getFinancials();

        $financialsElement = $this->createElement('financials');
        $supplierElement->appendChild($financialsElement);

        $financialsElement->appendChild($this->createNodeWithTextContent('duedays', $financials->getDueDays()));
        $financialsElement->appendChild($this->createNodeWithTextContent('meansofpayment', $financials->getMeansOfPayment()));
        $financialsElement->appendChild($this->createNodeWithTextContent('payavailable', Util::formatBoolean($financials->getPayAvailable())));
        $financialsElement->appendChild($this->createNodeWithTextContent('paycode', Util::objectToStr($financials->getPayCode())));
        $financialsElement->appendChild($this->createNodeWithTextContent('relationsreference', $financials->getRelationsReference()));
        $financialsElement->appendChild($this->createNodeWithTextContent('substitutewith', Util::objectToStr($financials->getSubstituteWith())));
        $financialsElement->appendChild($this->createNodeWithTextContent('vatcode', Util::objectToStr($financials->getVatCode())));

        $childValidations = $financials->getChildValidations();

        if (!empty($childValidations)) {
            // Make childvalidations element
            $childValidationsElement = $this->createElement('childvalidations');
            $financialsElement->appendChild($childValidationsElement);

            // Go through each childvalidation assigned to the supplier financials
            foreach ($childValidations as $childValidation) {
                // Make childvalidation element
                $childValidationsElement->appendChild($this->createNodeWithTextContent('childvalidation', $childValidation->getElementValue(), $childValidation, array('level' => 'getLevel', 'type' => 'getType')));
            }
        }

        $addresses = $supplier->getAddresses();

        if (!empty($addresses)) {
            // Make addresses element
            $addressesElement = $this->createElement('addresses');
            $supplierElement->appendChild($addressesElement);

            // Go through each address assigned to the supplier
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

        $banks = $supplier->getBanks();

        if (!empty($banks)) {
            // Make banks element
            $banksElement = $this->createElement('banks');
            $supplierElement->appendChild($banksElement);

            // Go through each bank assigned to the supplier
            foreach ($banks as $bank) {
                // Make bank element
                $bankElement = $this->createElement('bank');
                $banksElement->appendChild($bankElement);

                $blocked = Util::formatBoolean($bank->getBlocked());

                if (!empty($blocked)) {
                    $bankElement->setAttribute('blocked', $blocked);
                }

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
        $supplierElement->appendChild($remittanceAdviceElement);

        $remittanceAdviceElement->appendChild($this->createNodeWithTextContent('sendmail', $supplier->getRemittanceAdviceSendMail()));
        $remittanceAdviceElement->appendChild($this->createNodeWithTextContent('sendtype', $supplier->getRemittanceAdviceSendType()));

        $group = Util::objectToStr($supplier->getGroup());

        if (!empty($group)) {
            $groupsElement = $this->createElement('groups');
            $groupsElement->appendChild($this->createNodeWithTextContent('group', $group));
            $supplierElement->appendChild($groupsElement);
        }

        $postingRules = $supplier->getPostingRules();

        if (!empty($postingRules)) {
            // Make postingrules element
            $postingRulesElement = $this->createElement('postingrules');
            $supplierElement->appendChild($postingRulesElement);

            // Go through each posting rule assigned to the supplier
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

                    // Go through each line assigned to the supplier posting rule
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
        $supplierElement->appendChild($paymentconditionsElement);

        $paymentconditionElement = $this->createElement('paymentcondition');
        $paymentconditionsElement->appendChild($paymentconditionElement);

        $paymentconditionElement->appendChild($this->createNodeWithTextContent('discountdays', $supplier->getPaymentConditionDiscountDays()));
        $paymentconditionElement->appendChild($this->createNodeWithTextContent('discountpercentage', $supplier->getPaymentConditionDiscountPercentage()));

        $blockedAccountPaymentConditionsElement = $this->createElement('blockedaccountpaymentconditions');
        $supplierElement->appendChild($blockedAccountPaymentConditionsElement);

        $blockedAccountPaymentConditionsElement->appendChild($this->createNodeWithTextContent('includevat', $supplier->getBlockedAccountPaymentConditionsIncludeVat()));
        $blockedAccountPaymentConditionsElement->appendChild($this->createNodeWithTextContent('percentage', $supplier->getBlockedAccountPaymentConditionsPercentage()));
    }
}
