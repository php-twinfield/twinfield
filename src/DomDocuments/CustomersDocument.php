<?php
namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Customer;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\Util;

/**
 * The Document Holder for making new XML customers. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new customer.
 *
 * @package PhpTwinfield
 * @subpackage Invoice\DOM
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class CustomersDocument extends BaseDocument
{
    /**
     * Multiple customers can be created at once by enclosing them by the dimensions element.
     *
     * @return string
     */
    protected function getRootTagName(): string
    {
        return 'dimensions';
    }

    /**
     * Turns a passed Customer class into the required markup for interacting
     * with Twinfield.
     *
     * @param Customer $customer
     */
    public function addCustomer(Customer $customer): void
    {
        $customerEl = $this->createElement("dimension");

        // Elements and their associated methods for customer
        $customerTags = array(
            'code'      => 'getCode',
            'name'      => 'getName',
            'type'      => 'getType',
            'website'   => 'getWebsite',
            'office'    => 'getOffice',
        );

        if ($customer->getOffice()) {
            $customerTags['office'] = 'getOffice';
        }

        if (!empty($customer->getStatus())) {
            $customerEl->setAttribute('status', $customer->getStatus());
        }

        // Go through each customer element and use the assigned method
        foreach ($customerTags as $tag => $method) {

            if($value = $customer->$method()) {
                // Make text node for method value
                $node = $this->createTextNode($value);

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $customerEl->appendChild($element);
            }
        }

        // Check if the financial information should be supplied
        if ($customer->getDueDays() > 0) {

            // Financial elements and their methods
            $financialsTags = array(
                'duedays'      => 'getDueDays',
                'payavailable' => 'getPayAvailable',
                'paycode'      => 'getPayCode',
                'vatcode'      => 'getVatCode',
                'ebilling'     => 'getEBilling',
                'ebillmail'    => 'getEBillMail',
            );

            // Make the financial element
            $financialElement = $this->createElement('financials');
            $customerEl->appendChild($financialElement);

            // Go through each financial element and use the assigned method
            foreach ($financialsTags as $tag => $method) {

                // Make the text node for the method value
                $nodeValue = $customer->$method();
                if (is_bool($nodeValue)) {
                    $nodeValue = ($nodeValue) ? 'true' : 'false';
                }
                $node = $this->createTextNode($nodeValue);

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $financialElement->appendChild($element);
            }

            //check if collectmandate should be set
            $collectMandate = $customer->getCollectMandate();

            if ($collectMandate !== null) {

                // Collect mandate elements and their methods
                $collectMandateTags = array(
                    'id'            => 'getID',
                    'signaturedate' => 'getSignatureDate',
                    'firstrundate'  => 'getFirstRunDate',
                );

                // Make the collectmandate element
                $collectMandateElement = $this->createElement('collectmandate');
                $financialElement->appendChild($collectMandateElement);

                // Go through each collectmandate element and use the assigned method
                foreach ($collectMandateTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($this->getValueFromCallback([$collectMandate, $method]));

                    // Make the actual element and assign the node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the full element
                    $collectMandateElement->appendChild($element);
                }
            }
        }

        //check if creditmanagement should be set
        if ($customer->getCreditManagement() !== null) {

            // Credit management elements and their methods
            $creditManagementTags = array(
                'responsibleuser'   => 'getResponsibleUser',
                'basecreditlimit'   => 'getBaseCreditLimit',
                'sendreminder'      => 'getSendReminder',
                'reminderemail'     => 'getReminderEmail',
                'blocked'           => 'getBlocked',
                'freetext1'         => 'getFreeText1',
                'freetext2'         => 'getFreeText2',
                'comment'           => 'getComment',
            );

            // Make the creditmanagement element
            $creditManagementElement = $this->createElement('creditmanagement');
            $customerEl->appendChild($creditManagementElement);

            // Go through each credit management element and use the assigned method
            foreach ($creditManagementTags as $tag => $method) {

                // Make the text node for the method value
                $nodeValue = $customer->getCreditManagement()->$method();
                if (is_bool($nodeValue)) {
                    $nodeValue = ($nodeValue) ? 'true' : 'false';
                }
                $node = $this->createTextNode($nodeValue);

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $creditManagementElement->appendChild($element);
            }
        }

        $addresses = $customer->getAddresses();
        if (!empty($addresses)) {

            // Address elements and their methods
            $addressTags = array(
                'name'      => 'getName',
                'contact'   => 'getContact',
                'country'   => 'getCountry',
                'city'      => 'getCity',
                'postcode'  => 'getPostcode',
                'telephone' => 'getTelephone',
                'telefax'   => 'getFax',
                'email'     => 'getEmail',
                'field1'    => 'getField1',
                'field2'    => 'getField2',
                'field3'    => 'getField3',
                'field4'    => 'getField4',
                'field5'    => 'getField5',
                'field6'    => 'getField6',
            );

            // Make addresses element
            $addressesElement = $this->createElement('addresses');
            $customerEl->appendChild($addressesElement);

            // Go through each address assigned to the customer
            foreach ($addresses as $address) {

                // Makes new address element
                $addressElement = $this->createElement('address');
                $addressesElement->appendChild($addressElement);

                // Set attributes
                $addressElement->setAttribute('default', $address->getDefault() ? 'true' : 'false');
                $addressElement->setAttribute('type', $address->getType());

                // Go through each address element and use the assigned method
                foreach ($addressTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($address->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $addressElement->appendChild($element);
                }
            }
        }

        $banks = $customer->getBanks();
        if (!empty($banks)) {

            // Bank elements and their methods
            $bankTags = array(
                'ascription'      => 'getAscription',
                'accountnumber'   => 'getAccountnumber',
                'bankname'        => 'getBankname',
                'biccode'         => 'getBiccode',
                'city'            => 'getCity',
                'country'         => 'getCountry',
                'iban'            => 'getIban',
                'natbiccode'      => 'getNatbiccode',
                'postcode'        => 'getPostcode',
                'state'           => 'getState',
            );

            // Make banks element
            $banksElement = $this->createElement('banks');
            $customerEl->appendChild($banksElement);

            // Go through each bank assigned to the customer
            /** @var CustomerBank $bank */
            foreach ($banks as $bank) {

                // Makes new bank element
                $bankElement = $this->createElement('bank');
                $banksElement->appendChild($bankElement);

                // Set attributes
                $bankElement->setAttribute('default', $bank->getDefault() ? 'true' : 'false');

                // Go through each bank element and use the assigned method
                foreach ($bankTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($bank->$method());

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $bankElement->appendChild($element);
                }

                // Bank address fields

                // Make the text nodes for the bank address fields
                $field2Node = $this->createTextNode($bank->getAddressField2());
                $field3Node = $this->createTextNode($bank->getAddressField3());

                // Make the actual elements and assign the text nodes
                $field2Element = $this->createElement('field2');
                $field3Element = $this->createElement('field3');
                $field2Element->appendChild($field2Node);
                $field3Element->appendChild($field3Node);

                // Combine the fields in an address element and add that to the bank element
                $addressElement = $this->createElement('address');
                $addressElement->appendChild($field2Element);
                $addressElement->appendChild($field3Element);
                $bankElement->appendChild($addressElement);
            }
        }

        $this->rootElement->appendChild($customerEl);
    }
}
