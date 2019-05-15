<?php

/* BrowseData
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* BrowseData API Connector
 * \PhpTwinfield\ApiConnectors\BrowseDataApiConnector
 * Available methods: getBrowseData, getBrowseDefinition, getBrowseFields
 */

// Run all or only some of the following examples
$executeListBrowseDefinition        = false;
$executeListBrowseFields            = false;
$executeGetBrowseData               = true;

$browseDataApiConnector = new \PhpTwinfield\ApiConnectors\BrowseDataApiConnector($connection);

/* Codes of all available Browse Definitions
 Browse code            Description
 000	                General ledger transactions
 010	                Transactions still to be matched
 020	                Transaction list
 100	                Customer transactions
 200	                Supplier transactions
 300	                Project transactions
 301	                Asset transactions
 400	                Cash transactions
 410	                Bank transactions
 900	                Cost centers
 030_1	                General Ledger (details)
 030_2	                General Ledger (details) (v2)
 031	                General Ledger (intercompany)
 040_1	                Annual Report (totals)
 050_1	                Annual Report (YTD)
 060	                Annual Report (totals multicurrency)
 130_1	                Customers
 130_2	                Customers (v2)
 164	                Credit Management
 230_1	                Suppliers
 230_2	                Suppliers (v2)
 302_1	                Fixed Assets
 610_1	                Time & Expenses (Totals)
 620	                Time & Expenses (Multicurrency)
 650_1	                Time & Expenses (Details)
 651_1	                Time & Expenses (Totals per week)
 652_1	                Time & Expenses (Totals per period)
 660_1	                Time & Expenses (Billing details)
 661_1	                Time & Expenses (Billing per week)
 662_1	                Time & Expenses (Billing per period)
 670	                Transaction summary
 680	                Bank link details
 690	                Vat Return status
 700	                Hierarchy access
 */

 /* BrowseDefinition
 * \PhpTwinfield\BrowseDefinition
 * Available getters: getCode, getColumns, getName, getOffice, getShortName, isVisible
 * Available setters: setCode, setName, setOffice, setShortName, setVisible, addColumn, removeColumn
 */

/* BrowseColumn
 * \PhpTwinfield\BrowseColumn
 * Available getters: getField, getFrom, getId, getLabel, getOperator, getTo, isAsk, isVisible
 * Available setters: setAsk, setField, setFrom, setId, setLabel, setOperator, setTo, setVisible
 */

if ($executeListBrowseDefinition) {
    try {
        $browseDefinition = $browseDataApiConnector->getBrowseDefinition('000');
    } catch (ResponseException $e) {
        $browseDefinition = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($browseDefinition);
    echo "</pre>";
}

/* BrowseField
 * \PhpTwinfield\BrowseField
 * Available getters: getCode, getDataType, getFinder, getOptions, isCanOrder
 * Available setters: setCanOrder, setCode, setDataType, setFinder, addOption
 */

/* BrowseFieldOption
 * \PhpTwinfield\BrowseFieldOption
 * Available getters: getCode, getName
 * Available setters: setCode, setName
 */

if ($executeListBrowseFields) {
    try {
        $browseFields = $browseDataApiConnector->getBrowseFields();
    } catch (ResponseException $e) {
        $browseFields = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($browseFields);
    echo "</pre>";
}

/* BrowseSortField
 * \PhpTwinfield\BrowseSortField
 * Available getters: getCode, getOrder
 * Available setters: setCode, setOrder
 */

/* BrowseData
 * \PhpTwinfield\BrowseData
 * Available getters: getFirst, getHeaders, getLast, getRows, getTotal
 * Available setters: setFirst, setLast, setTotal, addHeader, addRow
 */

 /* BrowseDataHeader
 * \PhpTwinfield\BrowseDataHeader
 * Available getters: getCode, getLabel, getType, isHideForUser
 * Available setters: setCode, setHideForUser, setLabel, setType
 */

/* BrowseDataRow
 * \PhpTwinfield\BrowseDataRow
 * Available getters: getCells, getCode, getLine, getNumber, getOffice
 * Available setters: setCode, setLine, setNumber, setOffice, addCell
 */

/* BrowseDataCell
 * \PhpTwinfield\BrowseDataCell
 * Available getters: getField, getType, getValue, isHideForUser
 * Available setters: setField, setHideForUser, setType, setValue
 */

if ($executeGetBrowseData) {
    // First, create the columns that you want to retrieve (see the browse definition for which columns are available)
    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.head.yearperiod')
        ->setLabel('Period')
        ->setVisible(true)
        ->setAsk(true)
        ->setOperator(\PhpTwinfield\Enums\BrowseColumnOperator::BETWEEN())
        ->setFrom('2018/01')
        ->setTo('2050/12');

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.head.code')
        ->setLabel('Transaction type')
        ->setVisible(true);

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.head.shortname')
        ->setLabel('Name')
        ->setVisible(true);

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.head.number')
        ->setLabel('Trans. no.')
        ->setVisible(true);

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.line.dim1')
        ->setLabel('General ledger')
        ->setVisible(true)
        ->setAsk(true)
        ->setOperator(\PhpTwinfield\Enums\BrowseColumnOperator::BETWEEN())
        ->setFrom('1300')
        ->setTo('1300');

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.head.curcode')
        ->setLabel('Currency')
        ->setVisible(true);

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.line.valuesigned')
        ->setLabel('Value')
        ->setVisible(true);

    $columns[] = (new \PhpTwinfield\BrowseColumn())
        ->setField('fin.trs.line.description')
        ->setLabel('Description')
        ->setVisible(true);

    // Second, create sort fields
    $sortFields[] = new \PhpTwinfield\BrowseSortField('fin.trs.head.code');

    try {
        // Get the browse data
        $browseData = $browseDataApiConnector->getBrowseData('000', $columns, $sortFields);
    } catch (ResponseException $e) {
        $browseData = $e->getReturnedObject();
    }
    
    echo "<pre>";
    print_r($browseData);
    echo "</pre>"; 
    
    echo "Browse Data<br />";
    echo "First: {$browseData->getFirst()}<br />";
    echo "Last: {$browseData->getLast()}<br />";
    echo "Total: {$browseData->getTotal()}<br /><br />";
    
    $browseDataHeaders = $browseData->getHeaders();
    $tableHeader = array("Result #", "Office", "Code", "Number", "Line");
    
    foreach ($browseDataHeaders as $browseDataHeader) {
        $tableHeader[] = $browseDataHeader->getLabel();
    }
       
    $browseDataRows = $browseData->getRows();
    
    foreach ($browseDataRows as $key => $browseDataRow) {
        $tableRows[$key][] = $key;
        $tableRows[$key][] = $browseDataRow->getOffice();
        $tableRows[$key][] = $browseDataRow->getCode();
        $tableRows[$key][] = $browseDataRow->getNumber();
        $tableRows[$key][] = $browseDataRow->getLine();
        
        $browseDataCells = $browseDataRow->getCells();
        
        foreach ($browseDataCells as $browseDataCell) {
            $tableRows[$key][] = $browseDataCell->getValue();
        }
    }
    
    ?>
    <table>
    <tr>
    <?php foreach ($tableHeader as $tableHeaderColumn) { echo "<th>{$tableHeaderColumn}</th>"; } ?>
    </tr>
    <?php foreach ($tableRows as $tableRow) { echo "<tr>"; foreach ($tableRow as $tableColumn) {  echo "<td>{$tableColumn}</td>";  } echo "</tr>"; } ?>
    </table>
    <style type="text/css">
        table {
            width:100%;
            border-collapse:collapse;
        }
        table td {
            padding:7px; border:#4e95f4 1px solid;
        }

        table tr:nth-child(odd) {
            background: #b8d1f3;
        }

        table tr:nth-child(even) {
            background: #dae5f4;
        }
    </style>
    <?php
}