<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\ProjectsDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\ProjectMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Project;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Response\ResponseException;
use PhpTwinfield\Services\FinderService;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Projects.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class ProjectApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Project based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Project       The requested Project or Project object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Project
    {
        // Make a request to read a single Project. Set the required values
        $request_project = new Request\Read\Project();
        $request_project
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_project);

        return ProjectMapper::map($response);
    }

    /**
     * Sends a Project instance to Twinfield to update or add.
     *
     * @param Project $project
     * @return Project
     * @throws Exception
     */
    public function send(Project $project): Project
    {
		foreach($this->sendAll([$project]) as $each) {
            return $each->unwrap();
        }
    }

    /**
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

            $projectsDocument = new ProjectsDocument();

            foreach ($chunk as $project) {
                $projectsDocument->addProject($project);
            }

            $responses[] = $this->sendXmlDocument($projectsDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): Project {
            return ProjectMapper::map($response);
        });
    }

    /**
     * List all projects.
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
     * @return Project[] The projects found.
     */
    public function listAll(
        string $pattern = '*',
        int $field = 0,
        int $firstRow = 1,
        int $maxRows = 100,
        array $options = []
    ): array {
        $forcedOptions['dimtype'] = "PRJ";
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options, $forcedOptions);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_PROJECTS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $projectListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(\PhpTwinfield\Project::class, $response->data, $projectListAllTags);
    }

    /**
     * Deletes a specific Project based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Project       The deleted Project or Project object with error message if it can't be found.
     * @throws Exception
     */
    public function delete(string $code, Office $office): Project
    {
        $project = self::get($code, $office);

        if ($project->getResult() == 1) {
            $project->setStatusFromString("deleted");

            try {
                $projectDeleted = self::send($project);
            } catch (ResponseException $e) {
                $projectDeleted = $e->getReturnedObject();
            }

            return $projectDeleted;
        } else {
            return $project;
        }
    }
}