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

	public function listAllUsers($officeCode = null, $accessRules = null, $mutualOffices = null, $pattern = '*',
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
