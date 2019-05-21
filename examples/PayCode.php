<?php

//PayCode

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* PayCode API Connector
 * \PhpTwinfield\ApiConnectors\PayCodeApiConnector
 * Available methods: listAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;

$payCodeApiConnector = new \PhpTwinfield\ApiConnectors\PayCodeApiConnector($connection);

/* List all pay codes
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
 *                         Available options:      office, paytype
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         paytype                 Specifies which type of pay and collect types should be returned.
 *                         Available values:       pay, collect
 *                         Usage:                  $options['paytype'] = 'pay';
 */

//List all with pattern "SEPA*", field 0 (= search code or number), firstRow 1, maxRows 10, options -> paytype = pay
if ($executeListAllWithFilter) {
    $options = array('paytype' => 'pay');

    try {
        $payCodes = $payCodeApiConnector->listAll("SEPA*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $payCodes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($payCodes);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $payCodes = $payCodeApiConnector->listAll();
    } catch (ResponseException $e) {
        $payCodes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($payCodes);
    echo "</pre>";
}

/* PayCode
 * \PhpTwinfield\PayCode
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($payCodes as $key => $payCode) {
        echo "PayCode {$key}<br />";
        echo "Code: {$payCode->getCode()}<br />";
        echo "Name: {$payCode->getName()}<br /><br />";
    }
}