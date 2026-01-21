<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\ProjectDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\ProjectMapper;
use PhpTwinfield\Project;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the Twinfield service easier when trying to retrieve or set information about
 * Transactions.
 *
 * @author Jelle Roorda <jelle@qlic.nl>
 */
class ProjectApiConnector extends BaseApiConnector
{
    /**
     * @param Project $project
     * @return Project
     * @throws Exception
     */
    public function send(Project $project): Project
    {
        foreach ($this->sendAll([$project]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     *
     * @param Project[] $projects
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $projects): MappedResponseCollection
    {
        Assert::allIsInstanceOf($projects, Project::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($projects) as $chunk) {
            $projectDocument = new ProjectDocument;

            foreach ($chunk as $project) {
                $projectDocument->addProject($project);
            }

            $responses[] = $this->sendXmlDocument($projectDocument);

        }

        return $this->getProcessXmlService()->mapAll(
            $responses,
            "dimension",
            function (Response $subresponse): Project {
                return ProjectMapper::map($subresponse);
            }
        );
    }
}
