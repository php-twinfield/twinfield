<?php

//InvoiceType

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* InvoiceType API Connector
 * \PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector
 * Available methods: getInvoiceTypeVatType, listAll
 */

// Run all or only some of the following examples
$executeListAllWithFilter           = false;
$executeListAllWithoutFilter        = true;

$invoiceTypeApiConnector = new \PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector($connection);

/* List all invoice types
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
 *                         Available options:      office, vat
 *
 *                         office                  Sets the office code.
 *                         Usage:                  $options['office'] = 'SomeOfficeCode';
 *
 *                         vat                     Return only invoice types configured for including or excluding VAT.
 *                         Available values:       inclusive, exclusive
 *                         Usage:                  $options['vat'] = 'inclusive';
 */

//List all with pattern "INV*", field 0 (= search code or name), firstRow 1, maxRows 10, options, options -> vat = 'exclusive'
if ($executeListAllWithFilter) {
    $options = array('vat' => 'exclusive');

    try {
        $invoiceTypes = $invoiceTypeApiConnector->listAll("INV*", 0, 1, 10, $options);
    } catch (ResponseException $e) {
        $invoiceTypes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($invoiceTypes);
    echo "</pre>";
}

//List all with default settings (pattern '*', field 0, firstRow 1, maxRows 100, options [])
if ($executeListAllWithoutFilter) {
    try {
        $invoiceTypes = $invoiceTypeApiConnector->listAll();
    } catch (ResponseException $e) {
        $invoiceTypes = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($invoiceTypes);
    echo "</pre>";
}

/* InvoiceType
 * \PhpTwinfield\InvoiceType
 * Available getters: getCode, getName, getShortName
 * Available setters: setCode, setName, setShortName
 */

if ($executeListAllWithFilter || $executeListAllWithoutFilter) {
    foreach ($invoiceTypes as $key => $invoiceType) {
        echo "InvoiceType {$key}<br />";
        echo "Code: {$invoiceType->getCode()}<br />";
        echo "Name: {$invoiceType->getName()}<br />";
        echo "VAT: {$invoiceTypeApiConnector->getInvoiceTypeVatType($invoiceType->getCode())}<br /><br />";
    }
}