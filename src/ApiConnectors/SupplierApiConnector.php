<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Request as Request;
use PhpTwinfield\Supplier;
use PhpTwinfield\DomDocuments\SuppliersDocument;
use PhpTwinfield\Mappers\SupplierMapper;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Suppliers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 */
class SupplierApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific supplier based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param string $office Optional. If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Supplier|bool The requested supplier or false if it can't be found.
     */
    public function get($code, $office = null)
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
            $request_customer = new Request\Read\Supplier();
            $request_customer
                ->setOffice($office)
                ->setCode($code);

            // Send the Request document and set the response to this instance.
            $response = $service->send($request_customer);
            $this->setResponse($response);

            // Return a mapped Supplier if successful or false if not.
            if ($response->isSuccessful()) {
                return SupplierMapper::map($response);
            }
        }

        return false;
    }

    /**
     * Requests all customers from the List Dimension Type.
     *
     * @param null|string $office
     * @param string      $dimType
     * @return array A multidimensional array in the following form:
     *               [$supplierId => ['name' => $name, 'shortName' => $shortName], ...]
     */
    public function listAll(?string $office = null, string $dimType = 'CRD'): array
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
                $suppliers = [];

                // Store in an array by customer id
                foreach ($responseDOM->getElementsByTagName('dimension') as $supplier) {
                    $supplier_id = $supplier->textContent;

                    if (!is_numeric($supplier_id)) {
                        continue;
                    }

                    $suppliers[$supplier->textContent] = array(
                        'name' => $supplier->getAttribute('name'),
                        'shortName' => $supplier->getAttribute('shortname')
                    );
                }

                return $suppliers;
            }
        }

        return [];
    }

    /**
     * Sends a \PhpTwinfield\Supplier\Supplier instance to Twinfield to update or add.
     *
     * If you want to map the response back into a customer use getResponse()->getResponseDocument()->asXML() into the
     * SupplierMapper::map() method.
     *
     * @param Supplier $supplier
     * @return bool
     */
    public function send(Supplier $supplier): bool
    {
        // Attempts the process login
        if ($this->getLogin()->process()) {
            // Gets the secure service
            $service = $this->createService();

            // Gets a new instance of SuppliersDocument and sets the $supplier
            $suppliersDocument = new SuppliersDocument();
            $suppliersDocument->addSupplier($supplier);

            // Send the DOM document request and set the response
            $response = $service->send($suppliersDocument);
            $this->setResponse($response);

            // Return a bool on status of response.
            return $response->isSuccessful();
        }

        return false;
    }
}
