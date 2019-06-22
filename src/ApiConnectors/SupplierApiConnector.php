<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\SuppliersDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\SupplierMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Supplier;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Suppliers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Leon Rowland <leon@rowland.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 * @copyright (c) 2013, Pronamic
 */
class SupplierApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Supplier based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Supplier      The requested Supplier or Supplier object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Supplier
    {
        // Make a request to read a single supplier. Set the required values
        $request_supplier = new Request\Read\Supplier();
        $request_supplier
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_supplier);

        return SupplierMapper::map($response);
    }

    /**
     * Sends a Supplier instance to Twinfield to update or add.
     *
     * @param Supplier $supplier
     * @return Supplier
     * @throws Exception
     */
    public function send(Supplier $supplier): Supplier
    {
        foreach($this->sendAll([$supplier]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param Supplier[] $suppliers
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $suppliers): MappedResponseCollection
    {
        Assert::allIsInstanceOf($suppliers, Supplier::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($suppliers) as $chunk) {
            $suppliersDocument = new SuppliersDocument();

            foreach ($chunk as $supplier) {
                $suppliersDocument->addSupplier($supplier);
            }

            $responses[] = $this->sendXmlDocument($suppliersDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): Supplier {
           return SupplierMapper::map($response);
        });
    }

    /**
     * List all suppliers.
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
     * @return Supplier[] The suppliers found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $forcedOptions['dimtype'] = "CRD";
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options, $forcedOptions);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $supplierListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(Supplier::class, $response->data, $supplierListAllTags);
    }

    /**
     * Deletes a specific Supplier based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Supplier      The deleted Supplier or Supplier object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): Supplier
    {
        $supplier = self::get($code, $office);

        if ($supplier->getResult() == 1) {
            $supplier->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $supplierDeleted = self::send($supplier);
            } catch (ResponseException $e) {
                $supplierDeleted = $e->getReturnedObject();
            }

            return $supplierDeleted;
        } else {
            return $supplier;
        }
    }
}