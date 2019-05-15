<?php

//UserRole

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* UserRole API Connector
 * \PhpTwinfield\ApiConnectors\UserRoleApiConnector
 * Available methods: listAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;

$userRoleApiConnector = new \PhpTwinfield\ApiConnectors\UserRoleApiConnector($connection);

/* List all user roles
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
 *                         Available options:      office
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 */

//List all with pattern "LVL1*", field 0 (= search code or number), firstRow 1, maxRows 10
if ($executeListAllWithFilter) {
    try {
        $userRoles = $userRoleApiConnector->listAll("LVL1*", 0, 1, 10);
    } catch (ResponseException $e) {
        $userRoles = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($userRoles);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $userRoles = $userRoleApiConnector->listAll();
    } catch (ResponseException $e) {
        $userRoles = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($userRoles);
    echo "</pre>";
}

/* UserRole
 * \PhpTwinfield\UserRole
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($userRoles as $key => $userRole) {
        echo "UserRole {$key}<br />";
        echo "Code: {$userRole->getCode()}<br />";
        echo "Name: {$userRole->getName()}<br /><br />";
    }
}