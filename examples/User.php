<?php

/* User
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Settings/Access/Users/UserSettings
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* User API Connector
 * \PhpTwinfield\ApiConnectors\UserApiConnector
 * Available methods: get, listAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;
$executeRead                        = true;

$userApiConnector = new \PhpTwinfield\ApiConnectors\UserApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* List all users
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
 *                         Available options:      office, accessrules, mutualoffices
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         accessrules              Disabling the access rules is only possible if the user has access to user management.
 *                         Available values:       0 = false, 1 = true
 *                         Usage:                  $options['accessrules'] = 0;
 *
 *                         mutualoffices           Shows all users who have access to a selection of offices that overlaps with the Web Service user permissions.
 *                         Available values:       0 = false, 1 = true
 *                         Usage:                  $options['mutualoffices'] = 0;
 */

//List all with pattern "API*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> mutualoffices = 0
if ($executeListAllWithFilter) {
    $options = array('mutualoffices' => 0);

    try {
        $users = $userApiConnector->listAll("API*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $users = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($users);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $users = $userApiConnector->listAll();
    } catch (ResponseException $e) {
        $users = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($users);
    echo "</pre>";
}

/* User
 * \PhpTwinfield\User
 * Available getters: getAcceptExtraCost, getCode, getCreated, getCulture, getCultureName, getCultureNativeName, getDemo, getDemoLocked, getEmail, getExchangeQuota, getExchangeQuotaLocked, getFileManagerQuota,
 * getFileManagerQuotaLocked, getIsCurrentUser, getLevel, getMessages, getModified, getName, getPassword, getResult, getRole, getRoleLocked, getShortName, getStatus, getTouched, getType, getTypeLocked, hasMessages
 *
 * Available setters: fromCode, setAcceptExtraCost, setCode, setCulture, setCultureName, setCultureNativeName, setDemo, setDemoLocked, setEmail, setExchangeQuota, setExchangeQuotaLocked, setFileManagerQuota,
 * setFileManagerQuotaLocked, setIsCurrentUser, setLevel, setName, setPassword, setRole, setRoleLocked, setShortName, setStatus, setType, setTypeLocked
 *
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($users as $key => $user) {
        echo "User {$key}<br />";
        echo "Code: {$user->getCode()}<br />";
        echo "Name: {$user->getName()}<br /><br />";
    }
}

// Read a User based off the passed in code and optionally the office.
if ($executeRead) {
    try {
        $user = $userApiConnector->get("API000001", $office);
    } catch (ResponseException $e) {
        $user = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($user);
    echo "</pre>";

    echo "User<br />";
    echo "AcceptExtraCost (bool): {$user->getAcceptExtraCost()}<br />";                                                                 // bool|null                    Are extra costs accepted when changing the role (subscription) of the user.
    echo "AcceptExtraCost (string): " . Util::formatBoolean($user->getAcceptExtraCost()) . "<br />";                                    // string|null
    echo "Code: {$user->getCode()}<br />";                                                                                              // string|null                  Code of the user.
    echo "Created (\\DateTimeInterface): <pre>" . print_r($user->getCreated(), true) . "</pre><br />";                                  // DateTimeInterface|null       The date/time the user was created. Read-only attribute.
    echo "Created (string): " . Util::formatDateTime($user->getCreated()) . "<br />";                                                   // string|null
    echo "Culture: {$user->getCulture()}<br />";                                                                                        // Culture|null                 The culture in which Twinfield is shown.
    echo "CultureName: {$user->getCultureName()}<br />";                                                                                // string|null
    echo "CultureNativeName: {$user->getCultureNativeName()}<br />";                                                                    // string|null
    echo "Demo (bool): {$user->getDemo()}<br />";                                                                                       // bool|null                    Indicates whether the user will be used only for training purposes. Only available when type is equal to Client of accountant.
    echo "Demo (string): " . Util::formatBoolean($user->getDemo()) . "<br />";                                                          // string|null
    echo "DemoLocked (bool): {$user->getDemoLocked()}<br />";                                                                           // bool|null
    echo "DemoLocked (string): " . Util::formatBoolean($user->getDemoLocked()) . "<br />";                                              // string|null
    echo "Email: {$user->getEmail()}<br />";                                                                                            // string|null                  The user’s email address.
    echo "ExchangeQuota: {$user->getExchangeQuota()}<br />";                                                                            // int|null                     Twinfield Analysis quota.
    echo "ExchangeQuotaLocked (bool): {$user->getExchangeQuotaLocked()}<br />";                                                         // bool|null
    echo "ExchangeQuotaLocked (string): " . Util::formatBoolean($user->getExchangeQuotaLocked()) . "<br />";                            // string|null
    echo "FileManagerQuota: {$user->getFileManagerQuota()}<br />";                                                                      // int|null                     File Manager quota.
    echo "FileManagerQuotaLocked (bool): {$user->getFileManagerQuotaLocked()}<br />";                                                   // bool|null
    echo "FileManagerQuotaLocked (string): " . Util::formatBoolean($user->getFileManagerQuotaLocked()) . "<br />";                      // string|null
    echo "IsCurrentUser (bool): {$user->getIsCurrentUser()}<br />";                                                                     // bool|null
    echo "IsCurrentUser (string): " . Util::formatBoolean($user->getIsCurrentUser()) . "<br />";                                        // string|null
    echo "Level: {$user->getLevel()}<br />";                                                                                            // int|null

    if ($user->hasMessages()) {                                                                                                         // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($user->getMessages(), true) . "<br />";                                                             // Array|null                   (Error) messages.
    }

    echo "Modified (\\DateTimeInterface): <pre>" . print_r($user->getModified(), true) . "</pre><br />";                                // DateTimeInterface|null       The last date/time the user was modified. Read-only attribute.
    echo "Modified (string): " . Util::formatDateTime($user->getModified()) . "<br />";                                                 // string|null
    echo "Name: {$user->getName()}<br />";                                                                                              // string|null                  The name of the user.
    echo "Password: {$user->getPassword()}<br />";                                                                                      // string|null                  The password for the user.
    echo "Result: {$user->getResult()}<br />";                                                                                          // int|null                     Result (0 = error, 1 or empty = success).
    echo "Role (\\PhpTwinfield\\UserRole): <pre>" . print_r($user->getRole(), true) . "</pre><br />";                                   // UserRole|null                The role the user is linked to.
    echo "Role (string): " . Util::objectToStr($user->getRole()) . "<br />";                                                            // string|null
    echo "RoleLocked (bool): {$user->getRoleLocked()}<br />";                                                                           // bool|null
    echo "RoleLocked (string): " . Util::formatBoolean($user->getRoleLocked()) . "<br />";                                              // string|null
    echo "ShortName: {$user->getShortName()}<br />";                                                                                    // string|null                  The short name of the user.
    echo "Status: {$user->getStatus()}<br />";                                                                                          // Status|null                  For creating and updating status may be left empty. For deleting deleted should be used. In case a user that is used in a transaction is deleted, its status has been changed into hide. Hidden users can be activated by using active.
    echo "Touched: {$user->getTouched()}<br />";                                                                                        // int|null                     Count of the number of times the user settings are changed. Read-only attribute.
    echo "Type: {$user->getType()}<br />";                                                                                              // UserType|null                User type, will be validated with the office type. Use regular in case of a non-accountancy organisation.
    echo "TypeLocked (bool): {$user->getTypeLocked()}<br />";                                                                           // bool|null
    echo "TypeLocked (string): " . Util::formatBoolean($user->getTypeLocked()) . "<br />";                                              // string|null
}
