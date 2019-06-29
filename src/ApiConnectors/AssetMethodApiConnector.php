<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\AssetMethod;
use PhpTwinfield\DomDocuments\AssetMethodsDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\AssetMethodMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * AssetMethods.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class AssetMethodApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific AssetMethod based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return AssetMethod   The requested AssetMethod or AssetMethod object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): AssetMethod
    {
        // Make a request to read a single AssetMethod. Set the required values
        $request_assetMethod = new Request\AssetMethod();
        $request_assetMethod
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_assetMethod);

        return AssetMethodMapper::map($response);
    }

    /**
     * Sends an AssetMethod instance to Twinfield to update or add.
     *
     * @param AssetMethod $assetMethod
     * @return AssetMethod
     * @throws Exception
     */
    public function send(AssetMethod $assetMethod): AssetMethod
    {
		foreach($this->sendAll([$assetMethod]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param AssetMethod[] $assetMethods
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $assetMethods): MappedResponseCollection
    {
        Assert::allIsInstanceOf($assetMethods, AssetMethod::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($assetMethods) as $chunk) {

            $assetMethodsDocument = new AssetMethodsDocument();

            foreach ($chunk as $assetMethod) {
                $assetMethodsDocument->addAssetMethod($assetMethod);
            }

            $responses[] = $this->sendXmlDocument($assetMethodsDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "assetmethod", function(Response $response): AssetMethod {
            return AssetMethodMapper::map($response);
        });
    }

	/**
     * List all asset methods.
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
     * @return AssetMethod[] The asset methods found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_ASSET_METHODS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $assetMethodArrayListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(AssetMethod::class, $response->data, $assetMethodArrayListAllTags);
    }

    /**
     * Deletes a specific AssetMethod based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return AssetMethod   The deleted AssetMethod or AssetMethod object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): AssetMethod
    {
        $assetMethod = self::get($code, $office);

        if ($assetMethod->getResult() == 1) {
            $assetMethod->setStatus(\PhpTwinfield\Enums\Status::DELETED());

            try {
                $assetMethodDeleted = self::send($assetMethod);
            } catch (ResponseException $e) {
                $assetMethodDeleted = $e->getReturnedObject();
            }

            return $assetMethodDeleted;
        } else {
            return $assetMethod;
        }
    }
}
