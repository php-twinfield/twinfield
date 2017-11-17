<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Request as Request;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Customers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class CustomerApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific customer based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param string $office Optional. If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Customer|bool The requested customer or false if it can't be found.
     */
    public function get(string $code, ?string $office = null)
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            // Get the secure service class
            $service = $this->createService();

            // No office passed, get the office from the Config
            if (!$office) {
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
            }
        }

        return false;
    }

    /**
     * Requests all customers from the List Dimension Type.
     *
     * @param string|null $office
     * @param string      $dimType
     * @return array A multidimensional array in the following form:
     *               [$customerId => ['name' => $name, 'shortName' => $shortName], ...]
     */
    public function listAll(?string $office = null, string $dimType = 'DEB'): array
    {
        // Attempts to process the login
        if ($this->getLogin()->process()) {
            // Gets the secure service class
            $service = $this->createService();

            // If no office present, use the config set value
            if (!$office) {
                $office = $this->getConfig()->getOffice();
            }

            // Make a request to a list of all customers
            $request_customers = new Request\Catalog\Dimension($office, $dimType);

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_customers);
            $this->setResponse($response);

            // Loop through the results if successful
            if ($response->isSuccessful()) {
                // Get the raw response document
                $responseDOM = $response->getResponseDocument();

                // Prepared empty customer array
                $customers = [];

                // Store in an array by customer id
                foreach ($responseDOM->getElementsByTagName('dimension') as $customer) {
                    $customer_id = $customer->textContent;

                    if ($customer_id == $dimType) {
                        continue;
                    }

                    $customers[$customer->textContent] = array(
                        'name' => $customer->getAttribute('name'),
                        'shortName' => $customer->getAttribute('shortname')
                    );
                }

                return $customers;
            }
        }

        return [];
    }

    /**
     * Sends a \PhpTwinfield\Customer\Customer instance to Twinfield to update or add.
     *
     * If you want to map the response back into a customer use getResponse()->getResponseDocument()->asXML() into the
     * CustomerMapper::map() method.
     *
     * @param Customer $customer
     * @return bool
     */
    public function send(Customer $customer): bool
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            // Gets a new instance of CustomersDocument and sets the $customer
            $customersDocument = new CustomersDocument();
            $customersDocument->addCustomer($customer);

            // Send the DOM document request and set the response
            $response = $service->send($customersDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            return $response->isSuccessful();
        }

        return false;
    }
}
