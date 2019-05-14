<?php

//Country

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Country API Connector
 * \PhpTwinfield\ApiConnectors\CountryApiConnector
 * Available methods: listAll
 */

$countryApiConnector = new \PhpTwinfield\ApiConnectors\CountryApiConnector($connection);

/* List all countries
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

//List all with pattern "NL", field 0 (= search code or number), firstRow 1, maxRows 10
try {
    $countries = $countryApiConnector->listAll('NL', 0, 1, 10);
} catch (ResponseException $e) {
    $countries = $e->getReturnedObject();
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
try {
    $countries = $countryApiConnector->listAll();
} catch (ResponseException $e) {
    $countries = $e->getReturnedObject();
}

echo "<pre>";
print_r($countries);
echo "</pre>";

/* Country
 * \PhpTwinfield\Country
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

foreach ($countries as $key => $country) {
    echo "Country {$key}<br />";
    echo "Code: {$country->getCode()}<br />";
    echo "Name: {$country->getName()}<br /><br />";
}

// NOTE: Because the CountryApiConnector only supports the listAll method at the moment it is not particularly useful to create a new Country

$country = new \PhpTwinfield\Country;
$country->setCode("NL");
$country->setName("Nederland");
$country->setShortName("Nederland");

echo "<pre>";
print_r($country);
echo "</pre>";