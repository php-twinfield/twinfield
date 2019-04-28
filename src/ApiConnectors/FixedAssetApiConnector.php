<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\FixedAssetsDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\FixedAssetMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\FixedAsset;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * FixedAssets.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleApiConnector by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class FixedAssetApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific FixedAsset based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return FixedAsset|bool The requested fixedAsset or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): FixedAsset
    {
        // Make a request to read a single FixedAsset. Set the required values
        $request_fixedAsset = new Request\Read\FixedAsset();
        $request_fixedAsset
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_fixedAsset);

        return FixedAssetMapper::map($response);
    }

    /**
     * Sends a FixedAsset instance to Twinfield to update or add.
     *
     * @param FixedAsset $fixedAsset
     * @return FixedAsset
     * @throws Exception
     */
    public function send(FixedAsset $fixedAsset): FixedAsset
    {
		foreach($this->sendAll([$fixedAsset]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param FixedAsset[] $fixedAssets
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $fixedAssets): MappedResponseCollection
    {
        Assert::allIsInstanceOf($fixedAssets, FixedAsset::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($fixedAssets) as $chunk) {

            $fixedAssetsDocument = new FixedAssetsDocument();

            foreach ($chunk as $fixedAsset) {
                $fixedAssetsDocument->addFixedAsset($fixedAsset);
            }

            $response = $this->sendXmlDocument($fixedAssetsDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): FixedAsset {
            return FixedAssetMapper::map($response);
        });
    }

    /**
     * List all fixed assets.
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
     * @return FixedAsset[] The fixed assets found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = array('ArrayOfString' => array());

        unset($options['dimtype']);
        $optionsArrayOfString['ArrayOfString'][] = array("dimtype", "AST");

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $fixedAssets = [];
        foreach ($response->data->Items->ArrayOfString as $fixedAssetArray) {
            $fixedAsset = new FixedAsset();

            if (isset($fixedAssetArray->string[0])) {
                $fixedAsset->setCode($fixedAssetArray->string[0]);
                $fixedAsset->setName($fixedAssetArray->string[1]);
            } else {
                $fixedAsset->setCode($fixedAssetArray[0]);
                $fixedAsset->setName($fixedAssetArray[1]);
            }

            $fixedAssets[] = $fixedAsset;
        }

        return $fixedAssets;
    }
}