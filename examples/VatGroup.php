<?php

//VatGroup

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* VatGroup API Connector
 * \PhpTwinfield\ApiConnectors\VatGroupApiConnector
 * Available methods: listAll
 */

$vatGroupApiConnector = new \PhpTwinfield\ApiConnectors\VatGroupApiConnector($connection);

/* List all VAT groups
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

//List all with pattern "1*", field 0 (= search code or number), firstRow 5, maxRows 10
try {
    $vatGroups = $vatGroupApiConnector->listAll("1*", 0, 5, 10);
} catch (ResponseException $e) {
    $vatGroups = $e->getReturnedObject();
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
try {
    $vatGroups = $vatGroupApiConnector->listAll();
} catch (ResponseException $e) {
    $vatGroups = $e->getReturnedObject();
}

echo "<pre>";
print_r($vatGroups);
echo "</pre>";

/* VatGroup
 * \PhpTwinfield\VatGroup
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

foreach ($vatGroups as $key => $vatGroup) {
    echo "VatGroup {$key}<br />";
    echo "Code: {$vatGroup->getCode()}<br />";
    echo "Name: {$vatGroup->getName()}<br /><br />";
}

// NOTE: Because the VatGroupApiConnector only supports the listAll method at the moment it is not particularly useful to create a new VatGroup

$vatGroup = new \PhpTwinfield\VatGroup;
$vatGroup->setCode("1A");
$vatGroup->setName("High (1a)");
$vatGroup->setShortName("High1A");

echo "<pre>";
print_r($vatGroup);
echo "</pre>";