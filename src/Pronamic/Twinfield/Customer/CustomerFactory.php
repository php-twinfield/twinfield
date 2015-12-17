<?php
namespace Pronamic\Twinfield\Customer;

use \Pronamic\Twinfield\Factory\ParentFactory;
use \Pronamic\Twinfield\Customer\Mapper\CustomerMapper;
use \Pronamic\Twinfield\Request as Request;

/**
 * CustomerFactory
 * 
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about Customers.
 * 
 * Each function has detailed explanation over what is required, and what
 * happens.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see
 * the advanced guide detailing the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Customer
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class CustomerFactory extends ParentFactory
{
    /**
     * Requests a specific customer based off the passed in code
     * and office.
     * 
     * Office is an optional parameter.
     * 
     * First it attempts to login with the passed configuration into
     * this instances constructor.  If successful it will get the Service
     * class to handle further interactions.
     * 
     * If no office has been passed it will instead take the default office
     * from the passed in config class.
     * 
     * It makes a new instance of the Request\Read\Customer() and sets the
     * office and code parameters.
     * 
     * Using the Service class it will attempt to send the DOM document from
     * Read\Customer()
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will return a 
     * \Pronamic\Twinfield\Customer\Customer instance, made by the
     * \Pronamic\Twinfield\Customer\Mapper\CustomerMapper class.
     * 
     * @access public
     * @param int $code
     * @param int $office
     * @return \Pronamic\Twinfield\Customer\Customer | false
     */
    public function get($code, $office = null)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            
            // Get the secure service class
            $service = $this->getService();

            // No office passed, get the office from the Config
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to read a single customer. Set the required values
            $request_customer = new Request\Read\Customer();
            $request_customer
                ->setOffice($office)
                ->setCode($code);
            
            // Send the Request document and set the response to this instance.
            $response = $service->send($request_customer);
            $this->setResponse($response);

            // Return a mapped Customer if successful or false if not.
            if ($response->isSuccessful()) {
                return CustomerMapper::map($response);
            } else {
                return false;
            }
        }
    }
    
    /**
     * Requests all customers from the List Dimension Type.
     * 
     * First attempts to login with the passed configuration into this
     * instances constructor. If successful will get the Service class
     * to handle further interactions.
     * 
     * Makes a new instance of Catalog\Dimension and sets the office and
     * dimtype values.
     * 
     * Using the service class it will attempt to send the DOM document
     * from Catalog\Dimension.
     * 
     * It sets the response to this instances method setResponse() (which you
     * can access with getResponse())
     * 
     * If the response was successful it will loop through all the results
     * in the response and make an array of the customer ID as the array key
     * and the value being an array of 'name' and 'shortname'
     * 
     * If the response wasn't succesful it will return false.
     * 
     * @access public
     * @return array | false
     */
    public function listAll($office = null, $dimType = 'DEB')
    {

        // Attempts to process the login
        if ($this->getLogin()->process()) {

            // Gets the secure service class
            $service = $this->getService();

            // If no office present, use the config set value
            if (! $office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to a list of all customers
            $request_customers = new Request\Catalog\Dimension(
                $office,
                $dimType
            );

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_customers);
            $this->setResponse($response);

            // Loop through the results if successful
            if ($response->isSuccessful()) {

                // Get the raw response document
                $responseDOM = $response->getResponseDocument();

                // Prepared empty customer array
                $customers = array();

                // Store in an array by customer id
                foreach ($responseDOM->getElementsByTagName('dimension') as $customer) {
                    $customer_id = $customer->textContent;

                    if (! is_numeric($customer_id)) {
                        continue;
                    }

                    $customers[$customer->textContent] = array(
                        'name'      => $customer->getAttribute('name'),
                        'shortName' => $customer->getAttribute('shortname')
                    );
                }

                return $customers;
            }
        }
    }

    /**
     * Sends a \Pronamic\Twinfield\Customer\Customer instance to Twinfield
     * to update or add.
     * 
     * First attempts to login with the passed configuration in the constructor.
     * If successful will get the secure Service class.
     * 
     * It will then make an instance of 
     * \Pronamic\Twinfield\Customer\DOM\CustomersDocument where it will
     * pass in the Customer class in this methods parameter.
     * 
     * It will then attempt to send the DOM document from CustomersDocument
     * and set the response to this instances method setResponse() (which you
     * can get with getResponse())
     * 
     * If successful will return true, else will return false.
     * 
     * If you want to map the response back into a customer use getResponse()->
     * getResponseDocument()->asXML() into the CustomerMapper::map method.
     * 
     * @access public
     * @param \Pronamic\Twinfield\Customer\Customer $customer
     * @return boolean
     */
    public function send(Customer $customer)
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->getService();

            // Gets a new instance of CustomersDocument and sets the $customer
            $customersDocument = new DOM\CustomersDocument();
            $customersDocument->addCustomer($customer);

            // Send the DOM document request and set the response
            $response = $service->send($customersDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            if ($response->isSuccessful()) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * Returns the very next free code available for the Customer Code.  As adding a new customer still requires
     * a code.  This method first retrieves all customers, sorts by key, gets the last element, and increments the code
     * by one.
     * 
     * @access public
     * @return int
     */
    public function getFirstFreeCode()
    {
        // Get all customers
        $customers = $this->listAll();
        
        // Check some customers exist
        if (empty($customers)) {
            return 0;
        }
        
        // Get the keys
        $customersKeys = array_keys($customers);
        
        // Sort the keys and reverse to get the last first
        asort($customersKeys);
        $customersKeys = array_reverse($customersKeys);
        
        // Get the first of the reversed keys
        $latestCustomerCode = $customersKeys[0];
        
        // Increment by one and return.
        return (int) ++$latestCustomerCode;
    }
}
