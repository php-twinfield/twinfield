<?php

//CashBankBook

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* CashBankBook API Connector
 * \PhpTwinfield\ApiConnectors\CashBankBookApiConnector
 * Available methods: listAll
 */

$cashBankBookApiConnector = new \PhpTwinfield\ApiConnectors\CashBankBookApiConnector($connection);

/* List all cash and bank books
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
 *                         Available options:      office, banktype
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         banktype                Restricts the transaction types which will be returned.
 *                         Available values:       -1 = return both bank as well cash transaction types, 0 = return bank transaction types, 1 = return cash transaction types
 *                         Usage:                  $options['banktype'] = -1;
 */

//List all with pattern "BNK", field 0 (= search code or number), firstRow 1, maxRows 10, options -> banktype = 0
$options = array('banktype' => 0);

try {
    $cashBankBooks = $cashBankBookApiConnector->listAll('BNK', 0, 1, 10, $options);
} catch (ResponseException $e) {
    $cashBankBooks = $e->getReturnedObject();
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
try {
    $cashBankBooks = $cashBankBookApiConnector->listAll();
} catch (ResponseException $e) {
    $cashBankBooks = $e->getReturnedObject();
}

echo "<pre>";
print_r($cashBankBooks);
echo "</pre>";

/* CashBankBook
 * \PhpTwinfield\CashBankBook
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

foreach ($cashBankBooks as $key => $cashBankBook) {
    echo "CashBankBook {$key}<br />";
    echo "Code: {$cashBankBook->getCode()}<br />";
    echo "Name: {$cashBankBook->getName()}<br /><br />";
}

// NOTE: Because the CashBankBookApiConnector only supports the listAll method at the moment it is not particularly useful to create a new CashBankBook

$cashBankBook = new \PhpTwinfield\CashBankBook;
$cashBankBook->setCode("BNK");
$cashBankBook->setName("Standaard bank");
$cashBankBook->setShortName("StdBank");

echo "<pre>";
print_r($cashBankBook);
echo "</pre>";