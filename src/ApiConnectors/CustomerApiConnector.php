<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Customer;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\CustomerMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

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
     * Requests a specific customer based off the passed in code and the office.
     *
     * @param string $code
     * @param Office $office
     * @return Customer The requested customer
     * @throws Exception
     */
    public function get(string $code, Office $office): Customer
    {
        // Make a request to read a single customer. Set the required values
        $request_customer = new Request\Read\Customer();
        $request_customer
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_customer);
        return CustomerMapper::map($response);
    }

    /**
     * Requests all customers from the List Dimension Type.
     *
     * @param Office $office
     * @return array A multidimensional array in the following form:
     *               [$customerId => ['name' => $name, 'shortName' => $shortName], ...]
     *
     * @throws Exception
     */
    public function listAll(Office $office): array
    {
        // Make a request to a list of all customers
        $request_customers = new Request\Catalog\Dimension($office, "DEB");

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_customers);

        // Get the raw response document
        $responseDOM = $response->getResponseDocument();

        // Prepared empty customer array
        $customers = [];

        // Store in an array by customer id
        /** @var \DOMElement $customer */
        foreach ($responseDOM->getElementsByTagName('dimension') as $customer) {
            $customer_id = $customer->textContent;

            if ($customer_id == "DEB") {
                continue;
            }

            $customers[$customer->textContent] = array(
                'name' => $customer->getAttribute('name'),
                'shortName' => $customer->getAttribute('shortname'),
            );
        }

        return $customers;
    }

    /**
     * Sends a \PhpTwinfield\Customer\Customer instance to Twinfield to update or add.
     *
     * @param Customer $customer
     * @return Customer
     * @throws Exception
     */
    public function send(Customer $customer): Customer
    {
        $customerResponses = $this->sendAll([$customer]);

        Assert::count($customerResponses, 1);

        foreach ($customerResponses as $customerResponse) {
            return $customerResponse->unwrap();
        }
    }

    /**
     * @param Customer[] $customers
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $customers): MappedResponseCollection
    {
        Assert::allIsInstanceOf($customers, Customer::class);

        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($customers) as $chunk) {

            $customersDocument = new CustomersDocument();

            foreach ($chunk as $customer) {
                $customersDocument->addCustomer($customer);
            }

            $responses[] = $this->sendXmlDocument($customersDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): Customer {
           return CustomerMapper::map($response);
        });
    }
}
