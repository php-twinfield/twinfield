<?php
namespace PhpTwinfield\Mappers;

use PhpTwinfield\Response\Response;
use PhpTwinfield\Supplier;
use PhpTwinfield\SupplierAddress;
use PhpTwinfield\SupplierBank;

/**
 * Maps a response DOMDocument to the corresponding entity.
 * 
 * @package PhpTwinfield
 * @subpackage Mapper
 * @author Leon Rowland <leon@rowland.nl>
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
     */
    public static function map(Response $response)
    {
        // Generate new customer object
        $supplier = new Supplier();
        
        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Set the status attribute
        $dimensionElement = $responseDOM->getElementsByTagName('dimension')->item(0);
        $supplier->setStatus($dimensionElement->getAttribute('status'));

        // Supplier elements and their methods
        $supplierTags = array(
            'code'              => 'setCode',
            'uid'               => 'setUID',
            'name'              => 'setName',
            'inuse'             => 'setInUse',
            'behaviour'         => 'setBehaviour',
            'touched'           => 'setTouched',
            'beginperiod'       => 'setBeginPeriod',
            'endperiod'         => 'setEndPeriod',
            'endyear'           => 'setEndYear',
            'website'           => 'setWebsite',
            'editdimensionname' => 'setEditDimensionName',
            'office'            => 'setOffice',
        );

        // Loop through all the tags
        foreach ($supplierTags as $tag => $method) {
            self::setFromTagValue($responseDOM, $tag, [$supplier, $method]);
        }

        // Financial elements and their methods
        $financialsTags = array(
            'duedays'      => 'setDueDays',
            'payavailable' => 'setPayAvailable',
            'paycode'      => 'setPayCode',
            'ebilling'     => 'setEBilling',
            'ebillmail'    => 'setEBillMail'
        );

        // Financial elements
        $financialElement = $responseDOM->getElementsByTagName('financials')->item(0);

        if ($financialElement) {
            // Go through each financial element and add to the assigned method
            foreach ($financialsTags as $tag => $method) {

                // Get the dom element
                $_tag = $financialElement->getElementsByTagName($tag)->item(0);

                // If it has a value, set it to the associated method
                if (isset($_tag) && isset($_tag->textContent)) {
                    $value = $_tag->textContent;
                    if ($value == 'true' || $value == 'false') {
                        $value = $value == 'true';
                    }

                    $supplier->$method($value);
                }
            }
        }

        $addressesDOMTag = $responseDOM->getElementsByTagName('addresses');
        if (isset($addressesDOMTag) && $addressesDOMTag->length > 0) {

            // Element tags and their methods for address
            $addressTags = array(
                'name'      => 'setName',
                'contact'   => 'setContact',
                'country'   => 'setCountry',
                'city'      => 'setCity',
                'postcode'  => 'setPostcode',
                'telephone' => 'setTelephone',
                'telefax'   => 'setFax',
                'email'     => 'setEmail',
                'field1'    => 'setField1',
                'field2'    => 'setField2',
                'field3'    => 'setField3',
                'field4'    => 'setField4',
                'field5'    => 'setField5',
                'field6'    => 'setField6',
            );

            $addressesDOM = $addressesDOMTag->item(0);

            // Loop through each returned address for the customer
            foreach ($addressesDOM->getElementsByTagName('address') as $addressDOM) {

                // Make a new tempory SupplierAddress class
                $temp_address = new SupplierAddress();

                // Set the attributes ( id, type, default )
                $temp_address
                    ->setID($addressDOM->getAttribute('id'))
                    ->setType($addressDOM->getAttribute('type'))
                    ->setDefault($addressDOM->getAttribute('default'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($addressTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $addressDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $temp_address->$method($_tag->textContent);
                    }
                }

                // Add the address to the customer
                $supplier->addAddress($temp_address);

                // Clean that memory!
                unset($temp_address);
            }
        }

        $banksDOMTag = $responseDOM->getElementsByTagName('banks');
        if (isset($banksDOMTag) && $banksDOMTag->length > 0) {

            // Element tags and their methods for bank
            $bankTags = array(
                'ascription'      => 'setAscription',
                'accountnumber'   => 'setAccountnumber',
                'field2'          => 'setAddressField2',
                'field3'          => 'setAddressField3',
                'bankname'        => 'setBankname',
                'biccode'         => 'setBiccode',
                'city'            => 'setCity',
                'country'         => 'setCountry',
                'iban'            => 'setIban',
                'natbiccode'      => 'setNatbiccode',
                'postcode'        => 'setPostcode',
                'state'           => 'setState'
            );

            $banksDOM = $banksDOMTag->item(0);

            // Loop through each returned bank for the customer
            foreach ($banksDOM->getElementsByTagName('bank') as $bankDOM) {

                // Make a new tempory SupplierBank class
                $temp_bank = new SupplierBank();

                // Set the attributes ( id, default )
                $temp_bank
                    ->setID($bankDOM->getAttribute('id'))
                    ->setDefault($bankDOM->getAttribute('default'));

                // Loop through the element tags. Determine if it exists and set it if it does
                foreach ($bankTags as $tag => $method) {

                    // Get the dom element
                    $_tag = $bankDOM->getElementsByTagName($tag)->item(0);

                    // Check if the tag is set, and its content is set, to prevent DOMNode errors
                    if (isset($_tag) && isset($_tag->textContent)) {
                        $temp_bank->$method($_tag->textContent);
                    }
                }

                // Add the bank to the customer
                $supplier->addBank($temp_bank);

                // Clean that memory!
                unset($temp_bank);
            }
        }

        return $supplier;
    }
}
