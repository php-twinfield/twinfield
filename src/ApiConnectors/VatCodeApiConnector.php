<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\VatCodesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\VatCodeMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\VatCode;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * VatCodes.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Emile Bons <emile@emilebons.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class VatCodeApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific VatCode based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return VatCode       The requested VatCode or VatCode object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): VatCode
    {
        // Make a request to read a single VatCode. Set the required values
        $request_vatCode = new Request\Read\VatCode();
        $request_vatCode
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_vatCode);

        return VatCodeMapper::map($response);
    }

    /**
     * Sends a VatCode instance to Twinfield to update or add.
     *
     * @param VatCode $vatCode
     * @return VatCode
     * @throws Exception
     */
    public function send(VatCode $vatCode): VatCode
    {
        foreach($this->sendAll([$vatCode]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param VatCode[] $vatCodes
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $vatCodes): MappedResponseCollection
    {
        Assert::allIsInstanceOf($vatCodes, VatCode::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($vatCodes) as $chunk) {

            $vatCodesDocument = new VatCodesDocument();

            foreach ($chunk as $vatCode) {
                $vatCodesDocument->addVatCode($vatCode);
            }

            $responses[] = $this->sendXmlDocument($vatCodesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "vat", function(Response $response): VatCode {
            return VatCodeMapper::map($response);
        });
    }

    /**
     * List all VAT codes.
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
     * @return VatCode[] The VAT codes found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_VAT_CODES, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $vatCodeListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll("VatCode", $response->data, $vatCodeListAllTags);
    }
    
    /**
     * Deletes a specific VatCode based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return VatCode       The deleted VatCode or VatCode object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): VatCode
    {
        $vatCode = self::get($code, $office);
        
        if ($vatCode->getResult() == 1) {        
            $vatCode->setStatusFromString("deleted");

            try {
                $vatCodeDeleted = self::send($vatCode);
            } catch (ResponseException $e) {
                $vatCodeDeleted = $e->getReturnedObject();
            }

            return $vatCodeDeleted;
        } else {
            return $vatCode;
        }
    }
}