<?php

namespace Pronamic\Twinfield\User;

use Pronamic\Twinfield\Factory\FinderFactory;
use \Pronamic\Twinfield\Request as Request;

/**
 * UserFactory
 * 
 * A facade factory to make interaction with the the twinfield service easier
 * when trying to retrieve or send information about Users.
 * 
 * If you require more complex interactions or a heavier amount of control
 * over the requests to/from then look inside the methods or see
 * the advanced guide detailing the required usages.
 * 
 * @package Pronamic\Twinfield
 * @subpackage User
 * @author Emile Bons <emile@emilebons.nl>
 */
class UserFactory extends FinderFactory
{
    const ACCESS_RULES_DISABLED = 0;
    const ACCESS_RULES_ENABLED = 1;
    const MUTUAL_OFFICES_DISABLED = 0;
    const MUTUAL_OFFICES_ENABLED = 1;

    /**
     * List all users.
     * @param string $officeCode the office code, if only users from one office should be listed
     * @param integer $accessRules
     * @param integer $mutualOffices
     * @param string $pattern The search pattern. May contain wildcards * and ?
     * @param int $field The search field determines which field or fields will be searched. The available fields
     * depends on the finder type. Passing a value outside the specified values will cause an error.
     * @param int $firstRow First row to return, useful for paging
     * @param int $maxRows Maximum number of rows to return, useful for paging
     * @param array $options The Finder options. Passing an unsupported name or value causes an error. It's possible to
     * add multiple options. An option name may be used once, specifying an option multiple times will cause an error.
     * @return User[] the users found
     */
	public function listAll($officeCode = null, $accessRules = null, $mutualOffices = null, $pattern = '*',
        $field = 0, $firstRow = 1, $maxRows = 100, $options = array())
	{
        if(!is_null($officeCode)) {
            $options['office'] = $officeCode;
        }
        if(!is_null($accessRules)) {
            $options['accessRules'] = $accessRules;
        }
        if(!is_null($mutualOffices)) {
            $options['mutualOffices'] = $mutualOffices;
        }
		$response = $this->searchFinder(self::TYPE_USERS, $pattern, $field, $firstRow, $maxRows, $options);
        $users = [];
        foreach($response->data->Items->ArrayOfString as $userArray)
        {
            $user = new User();
            $user->setCode($userArray->string[0]);
            $user->setName($userArray->string[1]);
            $users[] = $user;
        }
        return $users;
	}
}
