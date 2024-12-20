<?php
namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Supplier;
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
class SuppliersDocument extends \DOMDocument
{
    /**
     * Holds the <dimension> element
     * that all additional elements should be a child of
     * @var \DOMElement
     */
    private $dimensionElement;

    /**
     * Creates the <dimension> element and adds it to the property
     * dimensionElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct('1.0', 'UTF-8');

        $this->dimensionElement = $this->createElement('dimension');
        $this->appendChild($this->dimensionElement);
    }

    /**
     * Turns a passed Supplier class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the invoice to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Supplier $supplier
     * @return void | [Adds to this instance]
     */
    public function addSupplier(Supplier $supplier)
    {
        // Elements and their associated methods for customer
        $supplierTags = array(
            'code'      => 'getCode',
            'name'      => 'getName',
            'type'      => 'getType',
            'website'   => 'getWebsite',
            'office'    => 'getOffice',
        );

        if ($supplier->getOffice()) {
            $supplierTags['office'] = 'getOffice';
        }

        $status = $supplier->getStatus();
        if (!empty($status)) {
            $this->dimensionElement->setAttribute('status', $status);
        }

        // Go through each customer element and use the assigned method
        foreach ($supplierTags as $tag => $method) {

            // Make text node for method value
            $node = $this->createTextNode($supplier->$method()  ?? '');

            // Make the actual element and assign the node
            $element = $this->createElement($tag);
            $element->appendChild($node);

            // Add the full element
            $this->dimensionElement->appendChild($element);
        }

        // Check if the financial information should be supplied
        if ($supplier->getDueDays() > 0) {

            // Financial elements and their methods
            $financialsTags = array(
                'duedays'      => 'getDueDays',
                'payavailable' => 'getPayAvailable',
                'paycode'      => 'getPayCode',
                'vatcode'      => 'getVatCode',
                'ebilling'     => 'getEBilling',
                'ebillmail'    => 'getEBillMail'
            );

            // Make the financial element
            $financialElement = $this->createElement('financials');
            $this->dimensionElement->appendChild($financialElement);

            // Go through each financial element and use the assigned method
            foreach ($financialsTags as $tag => $method) {

                // Make the text node for the method value
                $nodeValue = $supplier->$method();
                if (is_bool($nodeValue)) {
                    $nodeValue = ($nodeValue) ? 'true' : 'false';
                }
                $node = $this->createTextNode($nodeValue  ?? '');

                // Make the actual element and assign the node
                $element = $this->createElement($tag);
                $element->appendChild($node);

                // Add the full element
                $financialElement->appendChild($element);
            }
            $meansOfPayment = $supplier->getMeansOfPayment();

            if ($meansOfPayment !== null) {
                $meansOfPaymentElement = $this->createElement('meansofpayment', $meansOfPayment->getValue());
                $financialElement->appendChild($meansOfPaymentElement);
            }
        }


        $addresses = $supplier->getAddresses();
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
                'field6'    => 'getField6'
            );

            // Make addresses element
            $addressesElement = $this->createElement('addresses');
            $this->dimensionElement->appendChild($addressesElement);

            // Go through each address assigned to the customer
            foreach ($addresses as $address) {

                // Makes new address element
                $addressElement = $this->createElement('address');
                $addressesElement->appendChild($addressElement);

                // Set attributes
                $addressElement->setAttribute('default', Util::formatBoolean($address->getDefault()));
                $addressElement->setAttribute('type', $address->getType());

                // Go through each address element and use the assigned method
                foreach ($addressTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($address->$method()  ?? '');

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $addressElement->appendChild($element);
                }
            }
        }

        $banks = $supplier->getBanks();
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
                'state'           => 'getState'
            );

            // Make banks element
            $banksElement = $this->createElement('banks');
            $this->dimensionElement->appendChild($banksElement);

            // Go through each bank assigned to the customer
            foreach ($banks as $bank) {

                // Makes new bank element
                $bankElement = $this->createElement('bank');
                $banksElement->appendChild($bankElement);

                // Set attributes
                $bankElement->setAttribute('default', $bank->getDefault());

                // Go through each bank element and use the assigned method
                foreach ($bankTags as $tag => $method) {

                    // Make the text node for the method value
                    $node = $this->createTextNode($bank->$method()  ?? '');

                    // Make the actual element and assign the text node
                    $element = $this->createElement($tag);
                    $element->appendChild($node);

                    // Add the completed element
                    $bankElement->appendChild($element);
                }

                // Bank address fields

                // Make the text nodes for the bank address fields
                $field2Node = $this->createTextNode($bank->getAddressField2()  ?? '');
                $field3Node = $this->createTextNode($bank->getAddressField3()  ?? '');

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
    }
}
