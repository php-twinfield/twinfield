<?php
/* SalesTransaction
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Transactions
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/SalesTransactions
 */

//Optionally declare the namespace PhpTwinfield so u can call classes without prepending \PhpTwinfield\
namespace PhpTwinfield;

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Use the ResponseException class to handle errors when listing, getting and sending objects to/from Twinfield
use PhpTwinfield\Response\ResponseException;

// Use the Util class for helper functions
use PhpTwinfield\Util;

require_once('vendor/autoload.php');

// Retrieve an OAuth 2 connection
require_once('Connection.php');

/* Transaction API Connector
 * \PhpTwinfield\ApiConnectors\TransactionApiConnector
 * Available methods: delete, get, send, sendAll
 */

/* Note there is no list all functionality in the TransactionApiConnector.
 * Use the BrowseDataApiConnector to get information about transactions and then read a transaction using the TransactionApiConnector
 */

// Run all or only some of the following examples
$executeRead                        = true;
$executeCopy                        = false;
$executeNew                         = false;
$executeDelete                      = false;

$transactionApiConnector = new \PhpTwinfield\ApiConnectors\TransactionApiConnector($connection);

// Office code
$officeCode = "SomeOfficeCode";

// Create a new Office object from the $officeCode
$office = \PhpTwinfield\Office::fromCode($officeCode);

/* SalesTransaction
 * \PhpTwinfield\SalesTransaction
 * Available getters: getAutoBalanceVat, getBookingReference, getCode, getCurrency, getDate, getDueDate, getDateRaiseWarning, getDateRaiseWarningToString, getDestiny, getFreeText1, getFreeText2, getFreeText3, getInputDate, getInvoiceNumber, getInvoiceNumberRaiseWarning, getInvoiceNumberRaiseWarningToString, getLineClassName, getMessages, getModificationDate, getNumber, getOffice, getOrigin, getOriginReference, getPaymentReference, getPeriod, getRaiseWarning, getResult, hasMessages, getLines
 * Available setters: setAutoBalanceVat, setCode, setCurrency, setDate, setDueDate, setDateRaiseWarning, setDestiny, setFreeText1, setFreeText2, setFreeText3, setInputDate, setInvoiceNumber, setInvoiceNumberRaiseWarning, setLines, setModificationDate, setNumber, setOffice, setOrigin, setOriginReference, setPaymentReference, setPeriod, setRaiseWarning, addLine
 */

/* SalesTransactionLine
 * \PhpTwinfield\SalesTransactionLine
 * Available getters: getBaseValue, getBaseValueOpen, getBaseline, getComment, getDebitCredit, getDescription, getDestOffice, getDim1, getDim2, getDim3, getFreeChar, getID, getLineType, getMatchLevel, getMatchStatus, getMessages, getPerformanceCountry, getPerformanceDate, getPerformanceType, getPerformanceVatNumber, getRate, getReference, getRelation, getRepRate, getRepValue, getRepValueOpen, getResult, getSignedValue, getTransaction, getValue, getValueOpen, getVatBaseTotal, getVatBaseTurnover, getVatBaseValue, getVatCode, getVatRepTotal, getVatRepTurnover, getVatRepValue, getVatTotal, getVatTurnover, getVatValue, hasMessages
 * Available setters: setBaseValue, setBaseValueOpen, setBaseline, setComment, setDebitCredit, setDescription, setDestOffice, setDim1, setDim2, setDim3, setFreeChar, setID, setLineType, setMatchLevel, setMatchStatus, setPerformanceCountry, setPerformanceDate, setPerformanceType, setPerformanceVatNumber, setRate, setRelation, setRepRate, setRepValue, setRepValueOpen, setTransaction, setValue, setValueOpen, setVatBaseTotal, setVatBaseTurnover, setVatBaseValue, setVatCode, setVatRepTotal, setVatRepTurnover, setVatRepValue, setVatTotal, setVatTurnover, setVatValue
 */

/* Read a SalesTransaction based off the passed in sales day book code, transaction number and optionally the office.
 * The used transaction type, in the example below VRK, depends on the administration. It is possible that there are multiple sales day book codes in an administration.
 * See https://accounting.twinfield.com/UI/#/Settings/Company/TransactionTypes for available codes for (sales) day books in your office
 */

if ($executeRead) {
    try {
        $salesTransaction = $transactionApiConnector->get(\PhpTwinfield\SalesTransaction::class, "VRK", 201900011, $office);
    } catch (ResponseException $e) {
        $salesTransaction = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($salesTransaction);
    echo "</pre>";

    echo "SalesTransaction<br />";
    echo "AutoBalanceVat (bool): {$salesTransaction->getAutoBalanceVat()}<br />";                                                                            // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    echo "AutoBalanceVat (string): " . Util::formatBoolean($salesTransaction->getAutoBalanceVat()) . "<br />";                                               // string|null
    echo "BookingReference (\\PhpTwinfield\\BookingReference): <pre>" . print_r($salesTransaction->getBookingReference(), true) . "</pre><br />";            // BookingReference|null        The Booking reference
    echo "Code: {$salesTransaction->getCode()}<br />";                                                                                                       // string|null                  Transaction type code.
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($salesTransaction->getCurrency(), true) . "</pre><br />";                                    // Currency|null                Currency code.
    echo "Currency (string): " . Util::objectToStr($salesTransaction->getCurrency()) . "<br />";                                                             // string|null
    echo "Date (\\DateTimeInterface): <pre>" . print_r($salesTransaction->getDate(), true) . "</pre><br />";                                                 // DateTimeInterface|null       Transaction date.
    echo "Date (string): " . Util::formatDate($salesTransaction->getDate()) . "<br />";                                                                      // string|null
    echo "DateRaiseWarning (bool): {$salesTransaction->getDateRaiseWarning()}<br />";                                                                        // bool|null                    Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    echo "DateRaiseWarning (string): {$salesTransaction->getDateRaiseWarningToString()}<br />";                                                              // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    echo "Destiny: {$salesTransaction->getDestiny()}<br />";                                                                                                 // Destiny|null                 Attribute to indicate the destiny of the sales transaction. Only used in the request XML. temporary = sales transaction will be saved as provisional. final = sales transaction will be saved as final
    echo "DueDate (\\DateTimeInterface): <pre>" . print_r($salesTransaction->getDueDate(), true) . "</pre><br />";                                           // DateTimeInterface|null       Due date.
    echo "DueDate (string): " . Util::formatDate($salesTransaction->getDueDate()) . "<br />";                                                                // string|null
    echo "FreeText1: {$salesTransaction->getFreeText1()}<br />";                                                                                             // string|null                  Free text field 1 as entered on the transaction type.
    echo "FreeText2: {$salesTransaction->getFreeText2()}<br />";                                                                                             // string|null                  Free text field 2 as entered on the transaction type.
    echo "FreeText3: {$salesTransaction->getFreeText3()}<br />";                                                                                             // string|null                  Free text field 3 as entered on the transaction type.
    echo "InputDate (\\DateTimeInterface): <pre>" . print_r($salesTransaction->getInputDate(), true) . "</pre><br />";                                       // DateTimeInterface|null       The date/time on which the transaction was created. Read-only attribute.
    echo "InputDate (string): " . Util::formatDate($salesTransaction->getInputDate()) . "<br />";                                                            // string|null
    echo "InvoiceNumber: {$salesTransaction->getInvoiceNumber()}<br />";                                                                                     // string|null                  Invoice number.
    echo "InvoiceNumberRaiseWarning (bool): {$salesTransaction->getInvoiceNumberRaiseWarning()}<br />";                                                      // bool|null                    Optionally, it is possible to suppress warnings about 'double invoice numbers' by adding the raisewarning attribute and set its value to false.
    echo "InvoiceNumberRaiseWarning (string): {$salesTransaction->getInvoiceNumberRaiseWarningToString()}<br />";                                            // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.

    if ($salesTransaction->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($salesTransaction->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "ModificationDate (\\DateTimeInterface): <pre>" . print_r($salesTransaction->getModificationDate(), true) . "</pre><br />";                         // DateTimeInterface|null       The date/time on which the sales transaction was modified the last time. Read-only attribute.
    echo "ModificationDate (string): " . Util::formatDate($salesTransaction->getModificationDate()) . "<br />";                                              // string|null
    echo "Number: {$salesTransaction->getNumber()}<br />";                                                                                                   // int|null                     Transaction number. When creating a new sales transaction, don't include this tag as the transaction number is determined by the system. When updating a sales transaction, the related transaction number should be provided.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($salesTransaction->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($salesTransaction->getOffice()) . "<br />";                                                                 // string|null
    echo "Origin: {$salesTransaction->getOrigin()}<br />";                                                                                                   // string|null                  The sales transaction origin. Read-only attribute.
    echo "OriginReference: {$salesTransaction->getOriginReference()}<br />";                                                                                 // string|null                  The sales transaction origin reference (id). Provided in form of Guid. Read-only attribute.
    echo "PaymentReference: {$salesTransaction->getPaymentReference()}<br />";                                                                               // string|null                  Payment reference of the transaction. If payment reference is not specified, the paymentreference section must be omitted
    echo "Period: {$salesTransaction->getPeriod()}<br />";                                                                                                   // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    echo "RaiseWarning (bool): {$salesTransaction->getRaiseWarning()}<br />";                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    echo "RaiseWarning (string): " . Util::formatBoolean($salesTransaction->getRaiseWarning()) . "<br />";                                                   // string|null
    echo "Result: {$salesTransaction->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).

    $salesTransactionLines = $salesTransaction->getLines();                                                                                                   // array|null                   Array of SalesTransactionLine objects.

    foreach ($salesTransactionLines as $key => $salesTransactionLine) {
        echo "SalesTransactionLine {$key}<br />";

        echo "Baseline: {$salesTransactionLine->getBaseline()}<br />";                                                                                       // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
        echo "BaseValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getBaseValue(), true) . "</pre><br />";                                    // Money|null                   Amount in the base currency.
        echo "BaseValue (string): " . Util::formatMoney($salesTransactionLine->getBaseValue()) . "<br />";                                                   // string|null
        echo "BaseValueOpen (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getBaseValueOpen(), true) . "</pre><br />";                            // Money|null                   Only if line type is total. The amount still owed in base currency. Read-only attribute.
        echo "BaseValueOpen (string): " . Util::formatMoney($salesTransactionLine->getBaseValueOpen()) . "<br />";                                           // string|null
        echo "Comment: {$salesTransactionLine->getComment()}<br />";                                                                                         // string|null                  Comment set on the transaction line.
        echo "DebitCredit: {$salesTransactionLine->getDebitCredit()}<br />";                                                                                 // DebitCredit|null             If line type = total. In case of a 'normal' sales transaction debit. In case of a credit sales transaction credit. If line type = detail or vat. In case of a 'normal' sales transaction credit. In case of a credit sales transaction debit.
        echo "Description: {$salesTransactionLine->getDescription()}<br />";                                                                                 // string|null                  Description of the transaction line.
        echo "DestOffice (\\PhpTwinfield\\Office): <pre>" . print_r($salesTransactionLine->getDestOffice(), true) . "</pre><br />";                          // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
        echo "DestOffice (string): " . Util::objectToStr($salesTransactionLine->getDestOffice()) . "<br />";                                                 // string|null
        echo "Dim1: <pre>" . print_r($salesTransactionLine->getDim1(), true) . "</pre><br />";                                                               // object|null                  If line type = total the accounts receivable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the customer in Twinfield. If line type = detail the profit and loss account.
        echo "Dim1 (string): " . Util::objectToStr($salesTransactionLine->getDim1()) . "<br />";                                                             // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
        echo "Dim2: <pre>" . print_r($salesTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total the account receivable. If line type = detail the cost center or empty.
        echo "Dim2 (string): " . Util::objectToStr($salesTransactionLine->getDim2()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "Dim3: <pre>" . print_r($salesTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the project or asset or empty.
        echo "Dim3 (string): " . Util::objectToStr($salesTransactionLine->getDim3()) . "<br />";                                                             // string|null                  If line type = vat empty.
        echo "FreeChar: {$salesTransactionLine->getFreeChar()}<br />";                                                                                       // string|null                  Free character field. If line type is total and filled with N the sales invoice is excluded from direct debit runs done in Twinfield.
        echo "ID: {$salesTransactionLine->getID()}<br />";                                                                                                   // int|null                     Line ID.
        echo "LineType: {$salesTransactionLine->getLineType()}<br />";                                                                                       // LineType|null                Line type.
        echo "MatchLevel: {$salesTransactionLine->getMatchLevel()}<br />";                                                                                   // int|null                     Only if line type is total. The level of the matchable dimension. Read-only attribute.
        echo "MatchStatus: {$salesTransactionLine->getMatchStatus()}<br />";                                                                                 // MatchStatus|null             Payment status of the sales transaction. If line type detail or vat always notmatchable. Read-only attribute.

        if ($salesTransactionLine->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($salesTransactionLine->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
        }

        echo "PerformanceCountry (\\PhpTwinfield\\Country): <pre>" . print_r($salesTransactionLine->getPerformanceCountry(), true) . "</pre><br />";         // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
        echo "PerformanceCountry (string): " . Util::objectToStr($salesTransactionLine->getPerformanceCountry()) . "<br />";                                 // string|null
        echo "PerformanceDate (\\DateTimeInterface): <pre>" . print_r($salesTransactionLine->getPerformanceDate(), true) . "</pre><br />";                   // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
        echo "PerformanceDate (string): " . Util::formatDate($salesTransactionLine->getPerformanceDate()) . "<br />";                                        // string|null
        echo "PerformanceType: {$salesTransactionLine->getPerformanceType()}<br />";                                                                         // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
        echo "PerformanceVatNumber: {$salesTransactionLine->getPerformanceVatNumber()}<br />";                                                               // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
        echo "Rate: {$salesTransactionLine->getRate()}<br />";                                                                                               // float|null                   The exchange rate used for the calculation of the base amount.
        echo "Reference (\\PhpTwinfield\\MatchReference): <pre>" . print_r($salesTransactionLine->getReference(), true) . "</pre><br />";                    // MatchReference|null          Match reference
        echo "Relation: {$salesTransactionLine->getRelation()}<br />";                                                                                       // int|null                     Only if line type is total. Read-only attribute.
        echo "RepRate: {$salesTransactionLine->getRepRate()}<br />";                                                                                         // float|null                   The exchange rate used for the calculation of the reporting amount.
        echo "RepValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getRepValue(), true) . "</pre><br />";                                      // Money|null                   Amount in the reporting currency.
        echo "RepValue (string): " . Util::formatMoney($salesTransactionLine->getRepValue()) . "<br />";                                                     // string|null
        echo "RepValueOpen (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getRepValueOpen(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The amount still owed in reporting currency. Read-only attribute.
        echo "RepValueOpen (string): " . Util::formatMoney($salesTransactionLine->getRepValueOpen()) . "<br />";                                             // string|null
        echo "Result: {$salesTransactionLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "SignedValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getSignedValue(), true) . "</pre><br />";                                // Money|null
        echo "SignedValue (string): " . Util::formatMoney($salesTransactionLine->getSignedValue()) . "<br />";                                               // string|null
        echo "Value (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getValue(), true) . "</pre><br />";                                            // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
        echo "Value (string): " . Util::formatMoney($salesTransactionLine->getValue()) . "<br />";                                                           // string|null
        echo "ValueOpen (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getValueOpen(), true) . "</pre><br />";                                    // Money|null                   Only if line type is total. The amount still owed in the currency of the sales transaction. Read-only attribute.
        echo "ValueOpen (string): " . Util::formatMoney($salesTransactionLine->getValueOpen()) . "<br />";                                                   // string|null
        echo "VatBaseTotal (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatBaseTotal(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The total VAT amount in base currency.
        echo "VatBaseTotal (string): " . Util::formatMoney($salesTransactionLine->getVatBaseTotal()) . "<br />";                                             // string|null
        echo "VatBaseTurnover (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatBaseTurnover(), true) . "</pre><br />";                        // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
        echo "VatBaseTurnover (string): " . Util::formatMoney($salesTransactionLine->getVatBaseTurnover()) . "<br />";                                       // string|null
        echo "VatBaseValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatBaseValue(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. VAT amount in base currency.
        echo "VatBaseValue (string): " . Util::formatMoney($salesTransactionLine->getVatBaseValue()) . "<br />";                                             // string|null
        echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($salesTransactionLine->getVatCode(), true) . "</pre><br />";                               // VatCode|null                 Only if line type is detail or vat. VAT code.
        echo "VatCode (string): " . Util::objectToStr($salesTransactionLine->getVatCode()) . "<br />";                                                       // string|null
        echo "VatRepTotal (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatRepTotal(), true) . "</pre><br />";                                // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
        echo "VatRepTotal (string): " . Util::formatMoney($salesTransactionLine->getVatRepTotal()) . "<br />";                                               // string|null
        echo "VatRepTurnover (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatRepTurnover(), true) . "</pre><br />";                          // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
        echo "VatRepTurnover (string): " . Util::formatMoney($salesTransactionLine->getVatRepTurnover()) . "<br />";                                         // string|null
        echo "VatRepValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatRepValue(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepValue (string): " . Util::formatMoney($salesTransactionLine->getVatRepValue()) . "<br />";                                               // string|null
        echo "VatTotal (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatTotal(), true) . "</pre><br />";                                      // Money|null                   Only if line type is total. The total VAT amount in the currency of the sales transaction.
        echo "VatTotal (string): " . Util::formatMoney($salesTransactionLine->getVatTotal()) . "<br />";                                                     // string|null
        echo "VatTurnover (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatTurnover(), true) . "</pre><br />";                                // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the sales transaction.
        echo "VatTurnover (string): " . Util::formatMoney($salesTransactionLine->getVatTurnover()) . "<br />";                                               // string|null
        echo "VatValue (\\Money\\Money): <pre>" . print_r($salesTransactionLine->getVatValue(), true) . "</pre><br />";                                      // Money|null                   Only if line type is detail. VAT amount in the currency of the sales transaction.
        echo "VatValue (string): " . Util::formatMoney($salesTransactionLine->getVatValue()) . "<br />";                                                     // string|null
    }
}

// Copy an existing SalesTransaction to a new entity
if ($executeCopy) {
    try {
        $salesTransaction = $transactionApiConnector->get(\PhpTwinfield\SalesTransaction::class, "VRK", 201900011, $office);
    } catch (ResponseException $e) {
        $salesTransaction = $e->getReturnedObject();
    }

    $salesTransaction->setNumber(null);

    //Optional, but recommended so your copy is not posted to final immediately
    $salesTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());

    try {
        $salesTransactionCopy = $transactionApiConnector->send($salesTransaction);
    } catch (ResponseException $e) {
        $salesTransactionCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($salesTransactionCopy);
    echo "</pre>";

    echo "Result of copy process: {$salesTransactionCopy->getResult()}<br />";
    echo "Number of copied SalesTransaction: {$salesTransactionCopy->getNumber()}<br />";
}

// Create a new SalesTransaction from scratch, alternatively read an existing SalesTransaction as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $salesTransaction = new \PhpTwinfield\SalesTransaction;

    // Required values for creating a new SalesTransaction
    $salesTransaction->setCode('VRK');                                                                                                                       // string|null                  Transaction type code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $salesTransaction->setCurrency($currency);                                                                                                               // Currency|null                Currency code.
    //$salesTransaction->setCurrency(\PhpTwinfield\Currency::fromCode("EUR"));                                                                               // string|null
    $salesTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());                                                                                 // Destiny|null                 Attribute to indicate the destiny of the sales transaction. Only used in the request XML.
    //$salesTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::FINAL());                                                                                   // Destiny|null                 temporary = sales transaction will be saved as provisional. final = sales transaction will be saved as final.
    $salesTransaction->setInvoiceNumber(201900011);                                                                                                          // string|null                  Invoice number.
    $salesTransaction->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $salesTransaction->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null

    // Optional values for creating a new SalesTransaction
    $salesTransaction->setAutoBalanceVat(true);                                                                                                              // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    $date = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    $salesTransaction->setDate($date);                                                                                                                       // DateTimeInterface|null       Transaction date. Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    $salesTransaction->setDate(Util::parseDate("20190901"));                                                                                                 // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    $salesTransaction->setDateRaiseWarning(false);                                                                                                           // bool|null
    $dueDate = \DateTime::createFromFormat('d-m-Y', '01-10-2019');
    $salesTransaction->setDueDate($dueDate);                                                                                                                 // DateTimeInterface|null       Due date.
    $salesTransaction->setDueDate(Util::parseDate("20191001"));                                                                                              // string|null
    //$salesTransaction->setFreeText1('Example FreeText 1');                                                                                                 // string|null                  Free text field 1 as entered on the transaction type.
    //$salesTransaction->setFreeText2('Example FreeText 2');                                                                                                 // string|null                  Free text field 2 as entered on the transaction type.
    //$salesTransaction->setFreeText3('Example FreeText 3');                                                                                                 // string|null                  Free text field 3 as entered on the transaction type.
    $salesTransaction->setInvoiceNumberRaiseWarning(false);                                                                                                  // bool|null                    Optionally, it is possible to suppress warnings about 'double invoice numbers' by adding the raisewarning attribute and set its value to false. This overwrites the value of the raisewarning attribute as set on the root element.
    //$salesTransaction->setNumber(201900011);                                                                                                               // int|null                     Transaction number. When creating a new sales transaction, don't include this tag as the transaction number is determined by the system. When updating a sales transaction, the related transaction number should be provided.
    //$salesTransaction->setPeriod("2019/07");                                                                                                               // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    $salesTransaction->setRaiseWarning(true);                                                                                                                // bool|null                    Should warnings be given true or not false? Default true.

    // The minimum amount of SalesTransactionLines linked to an SalesTransaction object is 2 (1 total, 1 detail)
    $salesTransactionLine1 = new \PhpTwinfield\SalesTransactionLine;
    $salesTransactionLine1->setLineType(\PhpTwinfield\Enums\LineType::TOTAL());                                                                              // LineType|null                Line type.

    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('1300');
    $salesTransactionLine1->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the accounts receivable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the customer in Twinfield. If line type = detail the profit and loss account.
    $salesTransactionLine1->setDim1(\PhpTwinfield\GeneralLedger::fromCode('1300'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1000');
    $salesTransactionLine1->setDim2($dim2);                                                                                                                  // object|null                  If line type = total the account receivable. If line type = detail the cost center or empty. If line type = vat empty.
    $salesTransactionLine1->setDim2(\PhpTwinfield\Customer::fromCode('1000'));                                                                               // string|null
    $salesTransactionLine1->setID(1);                                                                                                                        // int|null            	     Line ID.
    $salesTransactionLine1->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
    //$salesTransactionLine1->setVatBaseTotal(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is total. The total VAT amount in base currency.
    //$salesTransactionLine1->setVatRepTotal(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
    //$salesTransactionLine1->setVatTotal(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is total. The total VAT amount in the currency of the sales transaction.

    $salesTransaction->addLine($salesTransactionLine1);                                                                                                      // SalesTransactionLine         Add a SalesTransactionLine object to the SalesTransaction object

    $salesTransactionLine2 = new \PhpTwinfield\SalesTransactionLine;
    $salesTransactionLine2->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $salesTransactionLine2->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $salesTransactionLine2->setDescription("Example Description on line with ID 2");                                                                         // string|null          	     Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $salesTransactionLine2->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the accounts receivable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the customer in Twinfield. If line type = detail the profit and loss account.
    $salesTransactionLine2->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $salesTransactionLine2->setID(2);                                                                                                                        // int|null            	     Line ID.
    $salesTransactionLine2->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.

    //$salesTransactionLine2->setComment('Example Comments');                                                                                                // string|null                  Comment set on the transaction line.
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('1234');
    //$salesTransaction->setDestOffice($destOffice);                                                                                                         // Office|null                  Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
    //$salesTransaction->setDestOffice(\PhpTwinfield\Office::fromCode('1234'));                                                                              // string|null
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1001');
    //$salesTransactionLine2->setDim2($dim2);                                                                                                                // object|null                  If line type = total the account receivable. If line type = detail the cost center or empty. If line type = vat empty.
    //$salesTransactionLine2->setDim2(\PhpTwinfield\Customer::fromCode('1001'));                                                                             // string|null
    $dim3 = new \PhpTwinfield\Project;
    $dim3->setCode('P0000');
    //$salesTransactionLine2->setDim3($dim3);                                                                                                                // object|null                  If line type = total empty. If line type = detail the project or asset or empty. If line type = vat empty.
    //$salesTransactionLine2->setDim3(\PhpTwinfield\Project::fromCode('P0000'));                                                                             // string|null
    //$salesTransactionLine2->setFreeChar('A');                                                                                                              // string|null                  Free character field. If line type is total and filled with N the sales invoice is excluded from direct debit runs done in Twinfield.
    $performanceCountry = new \PhpTwinfield\Country;
    $performanceCountry->setCode('NL');
    //$salesTransactionLine2->setPerformanceCountry($performanceCountry);                                                                                    // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
    //$salesTransactionLine2->setPerformanceCountry(\PhpTwinfield\Country::fromCode('NL'));                                                                  // string|null
    $performanceDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$salesTransactionLine2->setPerformanceDate($performanceDate);                                                                                          // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
    //$salesTransactionLine2->setPerformanceDate(Util::parseDate("20190901"));                                                                               // string|null
    //$salesTransactionLine2->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                           // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
    //$salesTransactionLine2->setPerformanceVatNumber('NL1234567890B01');                                                                                    // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
    //$salesTransactionLine2->setRepValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Amount in the reporting currency.
    //$salesTransactionLine2->setRate(1);                                                                                                                    // float|null                   The exchange rate used for the calculation of the base amount.
    //$salesTransactionLine2->setRepRate(1);                                                                                                                 // float|null                   The exchange rate used for the calculation of the reporting amount.
    //$salesTransactionLine2->setVatBaseValue(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is detail. VAT amount in base currency.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VN');
    //$salesTransactionLine2->setVatCode($vatCode);                                                                                                          // VatCode|null                 Only if line type is detail or vat. VAT code.
    //$salesTransactionLine2->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));                                                                             // string|null
    //$salesTransactionLine2->setVatRepValue(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is detail. VAT amount in reporting currency.
    //$salesTransactionLine2->setVatValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is detail. VAT amount in the currency of the sales transaction.

    $salesTransaction->addLine($salesTransactionLine2);

    //$salesTransactionLine3 = new \PhpTwinfield\SalesTransactionLine;
    //$salesTransactionLine3->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Line type.
    //$salesTransactionLine3->setBaseline(1);                                                                                                                // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
    //$salesTransactionLine3->setVatBaseTurnover(\Money\Money::EUR(-10000));                                                                                 // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
    //$salesTransactionLine3->setVatRepTurnover(\Money\Money::EUR(-10000));                                                                                  // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
    //$salesTransactionLine3->setVatTurnover(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the sales transaction.

    try {
        $salesTransactionNew = $transactionApiConnector->send($salesTransaction);
    } catch (ResponseException $e) {
        $salesTransactionNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($salesTransactionNew);
    echo "</pre>";

    echo "Result of creation process: {$salesTransactionNew->getResult()}<br />";
    echo "Number of new SalesTransaction: {$salesTransactionNew->getNumber()}<br />";
}

// Delete a BrankTransaction based off the passed in office, code, number and given reason
if ($executeDelete) {
    $bookingReference = new \PhpTwinfield\BookingReference($office, 'VRK', 201900012);

    try {
        $salesTransactionDeleted = $transactionApiConnector->delete($bookingReference, 'Example reason');
    } catch (ResponseException $e) {
        $salesTransactionDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($salesTransactionDeleted);
    echo "</pre>";
}