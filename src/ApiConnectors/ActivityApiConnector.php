<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\ActivitiesDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\ActivityMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\Activity;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Activities.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on ArticleApiConnector by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 */
class ActivityApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific Activity based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return Activity|bool The requested activity or false if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): Activity
    {
        // Make a request to read a single Activity. Set the required values
        $request_activity = new Request\Read\Activity();
        $request_activity
            ->setOffice($office->getCode())
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_activity);

        return ActivityMapper::map($response);
    }

    /**
     * Sends a Activity instance to Twinfield to update or add.
     *
     * @param Activity $activity
     * @return Activity
     * @throws Exception
     */
    public function send(Activity $activity): Activity
    {
		foreach($this->sendAll([$activity]) as $each) {
            return $each->unwrap();
        }
    }

    /**
     * @param Activity[] $activities
     * @return MappedResponseCollection
     * @throws Exception
     */
    public function sendAll(array $activities): MappedResponseCollection
    {
        Assert::allIsInstanceOf($activities, Activity::class);

        /** @var Response[] $responses */
        $responses = [];

        foreach ($this->getProcessXmlService()->chunk($activities) as $chunk) {

            $activitiesDocument = new ActivitiesDocument();

            foreach ($chunk as $activity) {
                $activitiesDocument->addActivity($activity);
            }

            $responses[] = $this->sendXmlDocument($activitiesDocument);
        }

        return $this->getProcessXmlService()->mapAll($responses, "dimension", function(Response $response): Activity {
            return ActivityMapper::map($response);
        });
    }

    /**
     * List all activities.
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
     * @return Activity[] The activities found.
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
        $optionsArrayOfString['ArrayOfString'][] = array("dimtype", "ACT");

        foreach ($options as $key => $value) {
            $optionsArrayOfString['ArrayOfString'][] = array($key, $value);
        }

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_DIMENSIONS_PROJECTS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        if ($response->data->TotalRows == 0) {
            return [];
        }

        $activities = [];

        foreach ($response->data->Items->ArrayOfString as $activityArray) {
            $activity = new Activity();

            if (isset($activityArray->string[0])) {
                $activity->setCode($activityArray->string[0]);
                $activity->setName($activityArray->string[1]);
            } else {
                $activity->setCode($activityArray[0]);
                $activity->setName($activityArray[1]);
            }

            $activities[] = $activity;
        }

        return $activities;
    }
}
