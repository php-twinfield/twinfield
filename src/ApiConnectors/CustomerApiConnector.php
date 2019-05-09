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
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Customers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class CustomerApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Customer based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Customer|bool The requested customer or false if it can't be found.
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
     * Sends a Customer instance to Twinfield to update or add.
     *
     * @param Customer $customer
     * @return Customer
     * @throws Exception
     */
    public function send(Customer $customer): Customer
    {
        foreach($this->sendAll([$customer]) as $each) {
            return $each->unwrap();
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

        /** @var Response[] $responses */
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

    /**
     * List all customers.
     *
     * @param string $pattern  The search pattern. May contain wildcards * and ?
     * @param int    $field    The search field determines which field or fields will be searched. The available fields
     *                         depends on the finder type. Passing a value outside the specified values will cause an
     *                         error.
     * @param int    $firstRow First row to return, useful for paging
     * @param int    $maxRows  Maximum number of rows to return, useful for paging
     * @param array  $options  The Finder options. Passing an unsupported name or value causes an error. It's possible
     *                         to add multiple options. An option name may be used once, specifying an option multiple
     *                         times will cause an error.
     *
     * @return Customer[] The customers found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $forcedOptions['dimtype'] = "DEB";
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options, $forcedOptions);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $customerListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll("Customer", $response->data, $customerListAllTags);
    }
}