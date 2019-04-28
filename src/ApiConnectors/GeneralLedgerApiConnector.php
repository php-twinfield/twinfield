<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\GeneralLedgersDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\GeneralLedgerMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\GeneralLedger;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * GeneralLedgers.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleApiConnector by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class GeneralLedgerApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific GeneralLedger based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return GeneralLedger|bool The requested generalLedger or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, string $dimType, Office $office)//: GeneralLedger
    {
        // Make a request to read a single GeneralLedger. Set the required values
        $request_generalLedger = new Request\Read\GeneralLedger();
        $request_generalLedger
            ->setOffice($office->getCode())
            ->setDimType($dimType)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_generalLedger);

        return GeneralLedgerMapper::map($response);
    }

    /**
     * Sends a GeneralLedger instance to Twinfield to update or add.
     *
     * @param GeneralLedger $generalLedger
     * @return GeneralLedger
     * @throws Exception
     */
    public function send(GeneralLedger $generalLedger): GeneralLedger
    {
		foreach($this->sendAll([$generalLedger]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param GeneralLedger[] $generalLedgers
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $generalLedgers): MappedResponseCollection
    {
        Assert::allIsInstanceOf($generalLedgers, GeneralLedger::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($generalLedgers) as $chunk) {
            $generalLedgersDocument = new GeneralLedgersDocument();

            foreach ($chunk as $generalLedger) {
                $generalLedgersDocument->addGeneralLedger($generalLedger);
            }

            $response = $this->sendXmlDocument($generalLedgersDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): GeneralLedger {
            return GeneralLedgerMapper::map($response);
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
     * @return GeneralLedger[] The fixed assets found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $optionsArrayOfString = array('ArrayOfString' => array());

        unset($options['level']);
        $optionsArrayOfString['ArrayOfString'][] = array("level", 1);

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_FINANCIALS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $generalLedgers = [];
        foreach ($response->data->Items->ArrayOfString as $generalLedgerArray) {
            $generalLedger = new GeneralLedger();

            if (isset($generalLedgerArray->string[0])) {
                $generalLedger->setCode($generalLedgerArray->string[0]);
                $generalLedger->setName($generalLedgerArray->string[1]);
            } else {
                $generalLedger->setCode($generalLedgerArray[0]);
                $generalLedger->setName($generalLedgerArray[1]);
            }

            $generalLedgers[] = $generalLedger;
        }

        return $generalLedgers;
    }
}