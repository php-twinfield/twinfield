<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\Exception;
use PhpTwinfield\Mappers\UserMapper;
use PhpTwinfield\Office;
use PhpTwinfield\Request as Request;
use PhpTwinfield\Response\MappedResponseCollection;
use PhpTwinfield\Response\Response;
use PhpTwinfield\Services\FinderService;
use PhpTwinfield\User;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Users.
 *
 * If you require more complex interactions or a heavier amount of control over the requests to/from then look inside
 * the methods or see the advanced guide detailing the required usages.
 *
 * @author Emile Bons <emile@emilebons.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class UserApiConnector extends BaseApiConnector
{
    /**
     * Requests a specific User based off the passed in code and optionally the office.
     *
     * @param string $code
     * @param Office $office If no office has been passed it will instead take the default office from the
     *                       passed in config class.
     * @return User          The requested User or User object with error message if it can't be found.
     * @throws Exception
     */
    public function get(string $code, Office $office): User
    {
        // Make a request to read a single User. Set the required values
        $request_user = new Request\Read\User();
        $request_user
            ->setOffice($office)
            ->setCode($code);

        // Send the Request document and set the response to this instance.
        $response = $this->sendXmlDocument($request_user);

        return UserMapper::map($response);
    }

    /**
     * List all users.
     *
     * @param string       $pattern       The search pattern. May contain wildcards * and ?
     * @param int          $field         The search field determines which field or fields will be searched. The
     *                                    available fields depends on the finder type. Passing a value outside the
     *                                    specified values will cause an error.
     * @param int          $firstRow      First row to return, useful for paging
     * @param int          $maxRows       Maximum number of rows to return, useful for paging
     * @param array        $options       The Finder options. Passing an unsupported name or value causes an error. It's
     *                                    possible to add multiple options. An option name may be used once, specifying
     *                                    an option multiple times will cause an error.
     *
     * @return User[] The users found.
     */
    public function listAll(
        $pattern = '*',
        $field = 0,
        $firstRow = 1,
        $maxRows = 100,
        $options = array()
    ): array {
        $optionsArrayOfString = $this->convertOptionsToArrayOfString($options);

        $response = $this->getFinderService()->searchFinder(FinderService::TYPE_USERS, $pattern, $field, $firstRow, $maxRows, $optionsArrayOfString);

        $userListAllTags = array(
            0       => 'setCode',
            1       => 'setName',
        );

        return $this->mapListAll(\PhpTwinfield\User::class, $response->data, $userListAllTags);
    }
}
