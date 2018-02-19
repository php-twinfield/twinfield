<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\MatchDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\MatchSetMapper;
use PhpTwinfield\MatchSet;
use PhpTwinfield\Response\IndividualMappedResponse;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * matching.
 */
class MatchesApiConnector extends BaseApiConnector
{
    /**
     * @throws Exception
     */
    public function send(MatchSet $matchSet): MatchSet
    {
        return $this->sendAll([$matchSet])[0]->unwrap();
    }

    /**
     * @return MappedResponseCollection|IndividualMappedResponse[]
     *
     * @throws Exception
     */
    public function sendAll(array $matchSets): MappedResponseCollection
    {
        Assert::allIsInstanceOf($matchSets, MatchSet::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($matchSets) as $chunk) {

            $document = new MatchDocument();

            foreach ($chunk as $matchSet) {
                $document->addMatchSet($matchSet);
            }

            $responses[] = $this->sendXmlDocument($document);
        }

        return $this->getProcessXmlService()->mapAll($responses, "set", function (Response $subResponse): MatchSet {
            return MatchSetMapper::map($subResponse->getResponseDocument());
        });
    }
}
