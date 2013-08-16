<?php
namespace Pronamic\Twinfield\Customer\Mapper;

use \Pronamic\Twinfield\Customer\Customer;
use \Pronamic\Twinfield\Customer\CustomerAddress;
use \Pronamic\Twinfield\Response\Response;

/**
 * Maps a response DOMDocument to the corresponding entity.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Mapper
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class CustomerMapper
{
    /**
     * Maps a Response object to a clean Customer entity.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Response\Response $response
     * @return \Pronamic\Twinfield\Customer\Customer
     */
    public static function map(Response $response)
    {
        // Generate new customer object
        $customer = new Customer();
        
        // Gets the raw DOMDocument response.
        $responseDOM = $response->getResponseDocument();

        // Customer elements and their methods
        $customerTags = array(
            'code'              => 'setID',
            'uid'               => 'setUID',
            'name'              => 'setName',
            'inuse'             => 'setInUse',
            'behaviour'         => 'setBehaviour',
            'touched'           => 'setTouched',
            'beginperiod'       => 'setBeginPeriod',
            'endperiod'         => 'setEndPeriod',
            'endyear'           => 'setEndYear',
            'website'           => 'setWebsite',
            'cocnumber'         => 'setCocNumber',
            'vatnumber'         => 'setVatNumber',
            'editdimensionname' => 'setEditDimensionName'
        );

        // Loop through all the tags
        foreach($customerTags as $tag => $method) {
            
            // Get the dom element
            $_tag = $responseDOM->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent))
                $customer->$method($_tag->textContent);
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

        // Go through each financial element and add to the assigned method
        foreach($financialsTags as $tag => $method) {
            
            // Get the dom element
            $_tag = $financialElement->getElementsByTagName($tag)->item(0);

            // If it has a value, set it to the associated method
            if(isset($_tag) && isset($_tag->textContent))
                $customer->$method($_tag->textContent);
        }

        // Element tags and their methods for address
        $address_tags = array(
            'name'      => 'setName',
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

        // Loop through each returned address for the customer
        foreach($responseDOM->getElementsByTagName('address') as $addressDOM) {
            
            // Make a new tempory CustomerAddress class
            $temp_address = new CustomerAddress();

            // Set the attributes ( id, type, default )
            $temp_address
                ->setID($addressDOM->getAttribute('id'))
                ->setType($addressDOM->getAttribute('type'))
                ->setDefault($addressDOM->getAttribute('default'));

            // Loop through the element tags. Determine if it exists and set it if it does
            foreach($address_tags as $tag => $method) {
                
                // Get the dom element
                $_tag = $addressDOM->getElementsByTagName($tag)->item(0);

                // Check if the tag is set, and its content is set, to prevent DOMNode errors
                if(isset($_tag) && isset($_tag->textContent))
                    $temp_address->$method($_tag->textContent);
            }

            // Add the address to the customer
            $customer->addAddress($temp_address);

            // Clean that memory!
            unset($temp_address);
        }

        return $customer;
    }
}
