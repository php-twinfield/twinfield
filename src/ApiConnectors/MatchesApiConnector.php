<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\MatchDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\MatchSet;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * matching.
 */
class MatchesApiConnector extends BaseApiConnector
{
    /**
     * @param MatchSet $matchSet
     * @throws Exception
     */
    public function send(MatchSet $matchSet): void
    {
        $this->sendAll([$matchSet]);
    }

    /**
     * @param MatchSet[] $matchSets
     * @throws Exception
     */
    public function sendAll(array $matchSets)
    {
        Assert::allIsInstanceOf($matchSets, MatchSet::class);

        foreach ($this->getProcessXmlService()->chunk($matchSets) as $chunk) {

            $document = new MatchDocument();

            foreach ($chunk as $matchSet) {
                $document->addMatchSet($matchSet);
            }

            $this->getProcessXmlService()->sendDocument($document);
        }
    }
}
