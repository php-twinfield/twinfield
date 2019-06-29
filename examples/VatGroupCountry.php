<?php

//VatGroupCountry

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* VatGroupCountry API Connector
 * \PhpTwinfield\ApiConnectors\VatGroupCountryApiConnector
 * Available methods: listAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;

$vatGroupCountryApiConnector = new \PhpTwinfield\ApiConnectors\VatGroupCountryApiConnector($connection);

/* List all VAT group countries
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
 *                         Available options:      office, country
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         country                 Specifies the country.
 *                         Usage:                  $options['country'] = 'NL';
 */

//List all with pattern "*", field 0 (= search code or name), firstRow 1, maxRows 10, options -> country = NL
if ($executeListAllWithFilter) {
    $options = array('country' => 'NL');

    try {
        $vatGroupCountries = $vatGroupCountryApiConnector->listAll('*', 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $vatGroupCountries = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatGroupCountries);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $vatGroupCountries = $vatGroupCountryApiConnector->listAll();
    } catch (ResponseException $e) {
        $vatGroupCountries = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($vatGroupCountries);
    echo "</pre>";
}

/* VatGroupCountry
 * \PhpTwinfield\VatGroupCountry
 * Available getters: getCode, getName, getShortName
 * Available setters: fromCode, setCode, setName, setShortName
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($vatGroupCountries as $key => $vatGroupCountry) {
        echo "VatGroupCountry {$key}<br />";
        echo "Code: {$vatGroupCountry->getCode()}<br />";
        echo "Name: {$vatGroupCountry->getName()}<br /><br />";
    }
}
