<?php
/* PurchaseTransaction
 * Twinfield UI:            https://accounting.twinfield.com/UI/#/Transactions
 * API Documentation:       https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/PurchaseTransactions
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

/* PurchaseTransaction
 * \PhpTwinfield\PurchaseTransaction
 * Available getters: getAutoBalanceVat, getBookingReference, getCode, getCurrency, getDate, getDueDate, getDateRaiseWarning, getDateRaiseWarningToString, getDestiny, getFreeText1, getFreeText2, getFreeText3, getInputDate, getInvoiceNumber, getInvoiceNumberRaiseWarning, getInvoiceNumberRaiseWarningToString, getLineClassName, getMessages, getModificationDate, getNumber, getOffice, getOrigin, getPaymentReference, getPeriod, getRaiseWarning, getResult, hasMessages, getLines
 * Available setters: setAutoBalanceVat, setCode, setCurrency, setDate, setDueDate, setDateRaiseWarning, setDestiny, setFreeText1, setFreeText2, setFreeText3, setInputDate, setInvoiceNumber, setInvoiceNumberRaiseWarning, setLines, setModificationDate, setNumber, setOffice, setOrigin, setPaymentReference, setPeriod, setRaiseWarning, addLine
 */

/* PurchaseTransactionLine
 * \PhpTwinfield\PurchaseTransactionLine
 * Available getters: getBaseValue, getBaseValueOpen, getBaseline, getComment, getDebitCredit, getDescription, getDestOffice, getDim1, getDim2, getDim3, getFreeChar, getID, getLineType, getMatchDate, getMatchLevel, getMatchStatus, getMessages, getRate, getReference, getRelation, getRepRate, getRepValue, getRepValueOpen, getResult, getSignedValue, getTransaction, getValue, getValueOpen, getVatBaseTotal, getVatBaseTurnover, getVatBaseValue, getVatCode, getVatRepTotal, getVatRepTurnover, getVatRepValue, getVatTotal, getVatTurnover, getVatValue, hasMessages
 * Available setters: setBaseValue, setBaseValueOpen, setBaseline, setComment, setDebitCredit, setDescription, setDestOffice, setDim1, setDim2, setDim3, setFreeChar, setID, setLineType, setMatchDate, setMatchLevel, setMatchStatus, setRate, setRelation, setRepRate, setRepValue, setRepValueOpen, setTransaction, setValue, setValueOpen, setVatBaseTotal, setVatBaseTurnover, setVatBaseValue, setVatCode, setVatRepTotal, setVatRepTurnover, setVatRepValue, setVatTotal, setVatTurnover, setVatValue
 */

/* Read a PurchaseTransaction based off the passed in purchase day book code, transaction number and optionally the office.
 * The used transaction type, in the example below INK, depends on the administration. It is possible that there are multiple purchase day book codes in an administration.
 * See https://accounting.twinfield.com/UI/#/Settings/Company/TransactionTypes for available codes for (purchase) day books in your office
 */

if ($executeRead) {
    try {
        $purchaseTransaction = $transactionApiConnector->get(\PhpTwinfield\PurchaseTransaction::class, "INK", 201900003, $office);
    } catch (ResponseException $e) {
        $purchaseTransaction = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($purchaseTransaction);
    echo "</pre>";

    echo "PurchaseTransaction<br />";
    echo "AutoBalanceVat (bool): {$purchaseTransaction->getAutoBalanceVat()}<br />";                                                                            // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    echo "AutoBalanceVat (string): " . Util::formatBoolean($purchaseTransaction->getAutoBalanceVat()) . "<br />";                                               // string|null
    echo "BookingReference (\\PhpTwinfield\\BookingReference): <pre>" . print_r($purchaseTransaction->getBookingReference(), true) . "</pre><br />";            // BookingReference|null        The Booking reference
    echo "Code: {$purchaseTransaction->getCode()}<br />";                                                                                                       // string|null                  Transaction type code.
    echo "Currency (\\PhpTwinfield\\Currency): <pre>" . print_r($purchaseTransaction->getCurrency(), true) . "</pre><br />";                                    // Currency|null                Currency code.
    echo "Currency (string): " . Util::objectToStr($purchaseTransaction->getCurrency()) . "<br />";                                                             // string|null
    echo "Date (\\DateTimeInterface): <pre>" . print_r($purchaseTransaction->getDate(), true) . "</pre><br />";                                                 // DateTimeInterface|null       Transaction date.
    echo "Date (string): " . Util::formatDate($purchaseTransaction->getDate()) . "<br />";                                                                      // string|null
    echo "DateRaiseWarning (bool): {$purchaseTransaction->getDateRaiseWarning()}<br />";                                                                        // bool|null                    Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    echo "DateRaiseWarning (string): {$purchaseTransaction->getDateRaiseWarningToString()}<br />";                                                              // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    echo "Destiny: {$purchaseTransaction->getDestiny()}<br />";                                                                                                 // Destiny|null                 Attribute to indicate the destiny of the purchase transaction. Only used in the request XML. temporary = purchase transaction will be saved as provisional. final = purchase transaction will be saved as final
    echo "DueDate (\\DateTimeInterface): <pre>" . print_r($purchaseTransaction->getDueDate(), true) . "</pre><br />";                                           // DateTimeInterface|null       Due date.
    echo "DueDate (string): " . Util::formatDate($purchaseTransaction->getDueDate()) . "<br />";                                                                // string|null
    echo "FreeText1: {$purchaseTransaction->getFreeText1()}<br />";                                                                                             // string|null                  Free text field 1 as entered on the transaction type.
    echo "FreeText2: {$purchaseTransaction->getFreeText2()}<br />";                                                                                             // string|null                  Free text field 2 as entered on the transaction type.
    echo "FreeText3: {$purchaseTransaction->getFreeText3()}<br />";                                                                                             // string|null                  Free text field 3 as entered on the transaction type.
    echo "InputDate (\\DateTimeInterface): <pre>" . print_r($purchaseTransaction->getInputDate(), true) . "</pre><br />";                                       // DateTimeInterface|null       The date/time on which the transaction was created. Read-only attribute.
    echo "InputDate (string): " . Util::formatDate($purchaseTransaction->getInputDate()) . "<br />";                                                            // string|null
    echo "InvoiceNumber: {$purchaseTransaction->getInvoiceNumber()}<br />";                                                                                     // string|null                  Invoice number.
    echo "InvoiceNumberRaiseWarning (bool): {$purchaseTransaction->getInvoiceNumberRaiseWarning()}<br />";                                                      // bool|null                    Optionally, it is possible to suppress warnings about 'double invoice numbers' by adding the raisewarning attribute and set its value to false.
    echo "InvoiceNumberRaiseWarning (string): {$purchaseTransaction->getInvoiceNumberRaiseWarningToString()}<br />";                                            // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.

    if ($purchaseTransaction->hasMessages()) {                                                                                                                  // bool                         Object contains (error) messages true/false.
        echo "Messages: " . print_r($purchaseTransaction->getMessages(), true) . "<br />";                                                                      // Array|null                   (Error) messages.
    }

    echo "ModificationDate (\\DateTimeInterface): <pre>" . print_r($purchaseTransaction->getModificationDate(), true) . "</pre><br />";                         // DateTimeInterface|null       The date/time on which the purchase transaction was modified the last time. Read-only attribute.
    echo "ModificationDate (string): " . Util::formatDate($purchaseTransaction->getModificationDate()) . "<br />";                                              // string|null
    echo "Number: {$purchaseTransaction->getNumber()}<br />";                                                                                                   // int|null                     Transaction number. When creating a new purchase transaction, don't include this tag as the transaction number is determined by the system. When updating a purchase transaction, the related transaction number should be provided.
    echo "Office (\\PhpTwinfield\\Office): <pre>" . print_r($purchaseTransaction->getOffice(), true) . "</pre><br />";                                          // Office|null                  Office code.
    echo "Office (string): " . Util::objectToStr($purchaseTransaction->getOffice()) . "<br />";                                                                 // string|null
    echo "Origin: {$purchaseTransaction->getOrigin()}<br />";                                                                                                   // string|null                  The purchase transaction origin. Read-only attribute.
    echo "PaymentReference: {$purchaseTransaction->getPaymentReference()}<br />";                                                                               // string|null                  Payment reference of the transaction. If payment reference is not specified, the paymentreference section must be omitted
    echo "Period: {$purchaseTransaction->getPeriod()}<br />";                                                                                                   // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    echo "RaiseWarning (bool): {$purchaseTransaction->getRaiseWarning()}<br />";                                                                                // bool|null                    Should warnings be given true or not false? Default true.
    echo "RaiseWarning (string): " . Util::formatBoolean($purchaseTransaction->getRaiseWarning()) . "<br />";                                                   // string|null
    echo "Result: {$purchaseTransaction->getResult()}<br />";                                                                                                   // int|null                     Result (0 = error, 1 or empty = success).

    $purchaseTransactionLines = $purchaseTransaction->getLines();                                                                                               // array|null                   Array of PurchaseTransactionLine objects.

    foreach ($purchaseTransactionLines as $key => $purchaseTransactionLine) {
        echo "PurchaseTransactionLine {$key}<br />";

        echo "Baseline: {$purchaseTransactionLine->getBaseline()}<br />";                                                                                       // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
        echo "BaseValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getBaseValue(), true) . "</pre><br />";                                    // Money|null                   Amount in the base currency.
        echo "BaseValue (string): " . Util::formatMoney($purchaseTransactionLine->getBaseValue()) . "<br />";                                                   // string|null
        echo "BaseValueOpen (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getBaseValueOpen(), true) . "</pre><br />";                            // Money|null                   Only if line type is total. The amount still to be paid in base currency. Read-only attribute.
        echo "BaseValueOpen (string): " . Util::formatMoney($purchaseTransactionLine->getBaseValueOpen()) . "<br />";                                           // string|null
        echo "Comment: {$purchaseTransactionLine->getComment()}<br />";                                                                                         // string|null                  Comment set on the transaction line.
        echo "DebitCredit: {$purchaseTransactionLine->getDebitCredit()}<br />";                                                                                 // DebitCredit|null             If line type = total. In case of a 'normal' purchase transaction credit. In case of a credit purchase transaction debit. If line type = detail or vat. In case of a 'normal' purchase transaction debit. In case of a credit purchase transaction credit.
        echo "Description: {$purchaseTransactionLine->getDescription()}<br />";                                                                                 // string|null                  Description of the transaction line.
        echo "DestOffice (\\PhpTwinfield\\Office): <pre>" . print_r($purchaseTransactionLine->getDestOffice(), true) . "</pre><br />";                          // Office|null                  Only if line type is detail. Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
        echo "DestOffice (string): " . Util::objectToStr($purchaseTransactionLine->getDestOffice()) . "<br />";                                                 // string|null
        echo "Dim1: <pre>" . print_r($purchaseTransactionLine->getDim1(), true) . "</pre><br />";                                                               // object|null                  If line type = total the accounts payable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the supplier in Twinfield. If line type = detail the profit and loss account.
        echo "Dim1 (string): " . Util::objectToStr($purchaseTransactionLine->getDim1()) . "<br />";                                                             // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
        echo "Dim2: <pre>" . print_r($purchaseTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total the account payable. If line type = detail the cost center or empty.
        echo "Dim2 (string): " . Util::objectToStr($purchaseTransactionLine->getDim2()) . "<br />";                                                             // string|null                  If line type = vat the cost center or empty.
        echo "Dim3: <pre>" . print_r($purchaseTransactionLine->getDim2(), true) . "</pre><br />";                                                               // object|null                  If line type = total empty. If line type = detail the project or asset or empty.
        echo "Dim3 (string): " . Util::objectToStr($purchaseTransactionLine->getDim3()) . "<br />";                                                             // string|null                  If line type = vat the project or asset or empty.
        echo "FreeChar: {$purchaseTransactionLine->getFreeChar()}<br />";                                                                                       // string|null                  Free character field. If line type is total and filled with N the purchase invoice is excluded from payment runs done in Twinfield.
        echo "ID: {$purchaseTransactionLine->getID()}<br />";                                                                                                   // int|null                     Line ID.
        echo "LineType: {$purchaseTransactionLine->getLineType()}<br />";                                                                                       // LineType|null                Line type.
        echo "MatchDate (\\DateTimeInterface): <pre>" . print_r($purchaseTransactionLine->getMatchDate(), true) . "</pre><br />";                               // DateTimeInterface|null       Only if line type is total. The date on which the purchase invoice is matched. Read-only attribute.
        echo "MatchDate (string): " . Util::formatDate($purchaseTransactionLine->getMatchDate()) . "<br />";                                                    // string|null
        echo "MatchLevel: {$purchaseTransactionLine->getMatchLevel()}<br />";                                                                                   // int|null                     Only if line type is total. The level of the matchable dimension. Read-only attribute.
        echo "MatchStatus: {$purchaseTransactionLine->getMatchStatus()}<br />";                                                                                 // MatchStatus|null             Payment status of the purchase transaction. If line type detail or vat always notmatchable. Read-only attribute.

        if ($purchaseTransactionLine->hasMessages()) {                                                                                                          // bool                         Object contains (error) messages true/false.
            echo "Messages: " . print_r($purchaseTransactionLine->getMessages(), true) . "<br />";                                                              // Array|null                   (Error) messages.
        }

        echo "Rate: {$purchaseTransactionLine->getRate()}<br />";                                                                                               // float|null                   The exchange rate used for the calculation of the base amount.
        echo "Reference (\\PhpTwinfield\\MatchReference): <pre>" . print_r($purchaseTransactionLine->getReference(), true) . "</pre><br />";                    // MatchReference|null          Match reference
        echo "Relation: {$purchaseTransactionLine->getRelation()}<br />";                                                                                       // int|null                     Only if line type is total. Read-only attribute.
        echo "RepRate: {$purchaseTransactionLine->getRepRate()}<br />";                                                                                         // float|null                   The exchange rate used for the calculation of the reporting amount.
        echo "RepValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getRepValue(), true) . "</pre><br />";                                      // Money|null                   Amount in the reporting currency.
        echo "RepValue (string): " . Util::formatMoney($purchaseTransactionLine->getRepValue()) . "<br />";                                                     // string|null
        echo "RepValueOpen (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getRepValueOpen(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The amount still to be paid in reporting currency. Read-only attribute.
        echo "RepValueOpen (string): " . Util::formatMoney($purchaseTransactionLine->getRepValueOpen()) . "<br />";                                             // string|null
        echo "Result: {$purchaseTransactionLine->getResult()}<br />";                                                                                           // int|null                     Result (0 = error, 1 or empty = success).
        echo "SignedValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getSignedValue(), true) . "</pre><br />";                                // Money|null
        echo "SignedValue (string): " . Util::formatMoney($purchaseTransactionLine->getSignedValue()) . "<br />";                                               // string|null
        echo "Value (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getValue(), true) . "</pre><br />";                                            // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
        echo "Value (string): " . Util::formatMoney($purchaseTransactionLine->getValue()) . "<br />";                                                           // string|null
        echo "ValueOpen (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getValueOpen(), true) . "</pre><br />";                                    // Money|null                   Only if line type is total. The amount still to be paid in the currency of the purchase transaction. Read-only attribute.
        echo "ValueOpen (string): " . Util::formatMoney($purchaseTransactionLine->getValueOpen()) . "<br />";                                                   // string|null
        echo "VatBaseTotal (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatBaseTotal(), true) . "</pre><br />";                              // Money|null                   Only if line type is total. The total VAT amount in base currency.
        echo "VatBaseTotal (string): " . Util::formatMoney($purchaseTransactionLine->getVatBaseTotal()) . "<br />";                                             // string|null
        echo "VatBaseTurnover (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatBaseTurnover(), true) . "</pre><br />";                        // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
        echo "VatBaseTurnover (string): " . Util::formatMoney($purchaseTransactionLine->getVatBaseTurnover()) . "<br />";                                       // string|null
        echo "VatBaseValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatBaseValue(), true) . "</pre><br />";                              // Money|null                   Only if line type is detail. VAT amount in base currency.
        echo "VatBaseValue (string): " . Util::formatMoney($purchaseTransactionLine->getVatBaseValue()) . "<br />";                                             // string|null
        echo "VatCode (\\PhpTwinfield\\VatCode): <pre>" . print_r($purchaseTransactionLine->getVatCode(), true) . "</pre><br />";                               // VatCode|null                 Only if line type is detail or vat. VAT code.
        echo "VatCode (string): " . Util::objectToStr($purchaseTransactionLine->getVatCode()) . "<br />";                                                       // string|null
        echo "VatRepTotal (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatRepTotal(), true) . "</pre><br />";                                // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
        echo "VatRepTotal (string): " . Util::formatMoney($purchaseTransactionLine->getVatRepTotal()) . "<br />";                                               // string|null
        echo "VatRepTurnover (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatRepTurnover(), true) . "</pre><br />";                          // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
        echo "VatRepTurnover (string): " . Util::formatMoney($purchaseTransactionLine->getVatRepTurnover()) . "<br />";                                         // string|null
        echo "VatRepValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatRepValue(), true) . "</pre><br />";                                // Money|null                   Only if line type is detail. VAT amount in reporting currency.
        echo "VatRepValue (string): " . Util::formatMoney($purchaseTransactionLine->getVatRepValue()) . "<br />";                                               // string|null
        echo "VatTotal (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatTotal(), true) . "</pre><br />";                                      // Money|null                   Only if line type is total. The total VAT amount in the currency of the purchase transaction.
        echo "VatTotal (string): " . Util::formatMoney($purchaseTransactionLine->getVatTotal()) . "<br />";                                                     // string|null
        echo "VatTurnover (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatTurnover(), true) . "</pre><br />";                                // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the purchase transaction.
        echo "VatTurnover (string): " . Util::formatMoney($purchaseTransactionLine->getVatTurnover()) . "<br />";                                               // string|null
        echo "VatValue (\\Money\\Money): <pre>" . print_r($purchaseTransactionLine->getVatValue(), true) . "</pre><br />";                                      // Money|null                   Only if line type is detail. VAT amount in the currency of the purchase transaction.
        echo "VatValue (string): " . Util::formatMoney($purchaseTransactionLine->getVatValue()) . "<br />";                                                     // string|null
    }
}

// Copy an existing PurchaseTransaction to a new entity
if ($executeCopy) {
    try {
        $purchaseTransaction = $transactionApiConnector->get(\PhpTwinfield\PurchaseTransaction::class, "INK", 201900003, $office);
    } catch (ResponseException $e) {
        $purchaseTransaction = $e->getReturnedObject();
    }

    $purchaseTransaction->setNumber(null);

    //Optional, but recommended so your copy is not posted to final immediately
    $purchaseTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());

    try {
        $purchaseTransactionCopy = $transactionApiConnector->send($purchaseTransaction);
    } catch (ResponseException $e) {
        $purchaseTransactionCopy = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($purchaseTransactionCopy);
    echo "</pre>";

    echo "Result of copy process: {$purchaseTransactionCopy->getResult()}<br />";
    echo "Number of copied PurchaseTransaction: {$purchaseTransactionCopy->getNumber()}<br />";
}

// Create a new PurchaseTransaction from scratch, alternatively read an existing PurchaseTransaction as shown above and than modify the values in the same way as shown below
if ($executeNew) {
    $purchaseTransaction = new \PhpTwinfield\PurchaseTransaction;

    // Required values for creating a new PurchaseTransaction
    $purchaseTransaction->setCode('INK');                                                                                                                       // string|null                  Transaction type code.
    $currency = new \PhpTwinfield\Currency;
    $currency->setCode('EUR');
    $purchaseTransaction->setCurrency($currency);                                                                                                               // Currency|null                Currency code.
    //$purchaseTransaction->setCurrency(\PhpTwinfield\Currency::fromCode("EUR"));                                                                               // string|null
    $purchaseTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::TEMPORARY());                                                                                 // Destiny|null                 Attribute to indicate the destiny of the purchase transaction. Only used in the request XML.
    //$purchaseTransaction->setDestiny(\PhpTwinfield\Enums\Destiny::FINAL());                                                                                   // Destiny|null                 temporary = purchase transaction will be saved as provisional. final = purchase transaction will be saved as final.
    $purchaseTransaction->setInvoiceNumber(201900011);                                                                                                          // string|null                  Invoice number.
    $purchaseTransaction->setOffice($office);                                                                                                                   // Office|null                  Office code.
    $purchaseTransaction->setOffice(\PhpTwinfield\Office::fromCode($officeCode));                                                                               // string|null

    // Optional values for creating a new PurchaseTransaction
    $purchaseTransaction->setAutoBalanceVat(true);                                                                                                              // bool|null                    Should VAT be rounded true or not false? Rounding will only be done with a maximum of two cents.
    $date = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    $purchaseTransaction->setDate($date);                                                                                                                       // DateTimeInterface|null       Transaction date. Optionally, it is possible to suppress warnings about 'date out of range for the given period' by adding the raisewarning attribute and set its value to false.
    $purchaseTransaction->setDate(Util::parseDate("20190901"));                                                                                                 // string|null                  This overwrites the value of the raisewarning attribute as set on the root element.
    $purchaseTransaction->setDateRaiseWarning(false);                                                                                                           // bool|null
    $dueDate = \DateTime::createFromFormat('d-m-Y', '01-10-2019');
    $purchaseTransaction->setDueDate($dueDate);                                                                                                                 // DateTimeInterface|null       Due date.
    $purchaseTransaction->setDueDate(Util::parseDate("20191001"));                                                                                              // string|null
    //$purchaseTransaction->setFreeText1('Example FreeText 1');                                                                                                 // string|null                  Free text field 1 as entered on the transaction type.
    //$purchaseTransaction->setFreeText2('Example FreeText 2');                                                                                                 // string|null                  Free text field 2 as entered on the transaction type.
    //$purchaseTransaction->setFreeText3('Example FreeText 3');                                                                                                 // string|null                  Free text field 3 as entered on the transaction type.
    $purchaseTransaction->setInvoiceNumberRaiseWarning(false);                                                                                                  // bool|null                    Optionally, it is possible to suppress warnings about 'double invoice numbers' by adding the raisewarning attribute and set its value to false. This overwrites the value of the raisewarning attribute as set on the root element.
    //$purchaseTransaction->setNumber(201900011);                                                                                                               // int|null                     Transaction number. When creating a new purchase transaction, don't include this tag as the transaction number is determined by the system. When updating a purchase transaction, the related transaction number should be provided.
    //$purchaseTransaction->setPeriod("2019/07");                                                                                                               // string|null                  Period in YYYY/PP format. If this tag is not included or if it is left empty, the period is determined by the system based on the provided transaction date.
    $purchaseTransaction->setRaiseWarning(true);                                                                                                                // bool|null                    Should warnings be given true or not false? Default true.

    // The minimum amount of PurchaseTransactionLines linked to an PurchaseTransaction object is 2 (1 total, 1 detail)
    $purchaseTransactionLine1 = new \PhpTwinfield\PurchaseTransactionLine;
    $purchaseTransactionLine1->setLineType(\PhpTwinfield\Enums\LineType::TOTAL());                                                                              // LineType|null                Line type.

    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('1600');
    $purchaseTransactionLine1->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the accounts payable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the supplier in Twinfield. If line type = detail the profit and loss account.
    $purchaseTransactionLine1->setDim1(\PhpTwinfield\GeneralLedger::fromCode('1600'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $dim2 = new \PhpTwinfield\Supplier;
    $dim2->setCode('2000');
    $purchaseTransactionLine1->setDim2($dim2);                                                                                                                  // object|null                  If line type = total the account payable. If line type = detail the cost center or empty. If line type = vat the cost center or empty.
    $purchaseTransactionLine1->setDim2(\PhpTwinfield\Supplier::fromCode('2000'));                                                                               // string|null
    $purchaseTransactionLine1->setID(1);                                                                                                                        // int|null            	        Line ID.
    $purchaseTransactionLine1->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.
    //$purchaseTransactionLine1->setVatBaseTotal(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is total. The total VAT amount in base currency.
    //$purchaseTransactionLine1->setVatRepTotal(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is total. The total VAT amount in reporting currency.
    //$purchaseTransactionLine1->setVatTotal(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is total. The total VAT amount in the currency of the purchase transaction.

    $purchaseTransaction->addLine($purchaseTransactionLine1);                                                                                                   // PurchaseTransactionLine      Add a PurchaseTransactionLine object to the PurchaseTransaction object

    $purchaseTransactionLine2 = new \PhpTwinfield\PurchaseTransactionLine;
    $purchaseTransactionLine2->setLineType(\PhpTwinfield\Enums\LineType::DETAIL());                                                                             // LineType|null                Line type.

    $purchaseTransactionLine2->setBaseValue(\Money\Money::EUR(10000));                                                                                          // Money|null                   Amount in the base currency.
    $purchaseTransactionLine2->setDescription("Example Description on line with ID 2");                                                                         // string|null          	    Description of the transaction line.
    $dim1 = new \PhpTwinfield\GeneralLedger;
    $dim1->setCode('2500');
    $purchaseTransactionLine2->setDim1($dim1);                                                                                                                  // object|null                  If line type = total the accounts payable balance account. When dim1 is omitted, by default the general ledger account will be taken as entered at the supplier in Twinfield. If line type = detail the profit and loss account.
    $purchaseTransactionLine2->setDim1(\PhpTwinfield\GeneralLedger::fromCode('2500'));                                                                          // string|null                  If line type = vat the VAT balance account. When an empty dim1 is entered, by default the general ledger account will be taken as entered at the VAT code in Twinfield.
    $purchaseTransactionLine2->setID(2);                                                                                                                        // int|null            	        Line ID.
    $purchaseTransactionLine2->setValue(\Money\Money::EUR(-10000));                                                                                             // Money|null                   If line type = total amount including VAT. If line type = detail amount without VAT. If line type = vat VAT amount.

    //$purchaseTransactionLine2->setComment('Example Comments');                                                                                                // string|null                  Comment set on the transaction line.
    $destOffice = new \PhpTwinfield\Office;
    $destOffice->setCode('1234');
    //$purchaseTransaction->setDestOffice($destOffice);                                                                                                         // Office|null                  Only if line type is detail. Office code. Used for inter company transactions – here you define in which company the transaction line should be posted.
    //$purchaseTransaction->setDestOffice(\PhpTwinfield\Office::fromCode('1234'));                                                                              // string|null
    $dim2 = new \PhpTwinfield\Customer;
    $dim2->setCode('1001');
    //$purchaseTransactionLine2->setDim2($dim2);                                                                                                                // object|null                  If line type = total the account payable. If line type = detail the cost center or empty. If line type = vat the cost center or empty.
    //$purchaseTransactionLine2->setDim2(\PhpTwinfield\Customer::fromCode('1001'));                                                                             // string|null
    $dim3 = new \PhpTwinfield\Project;
    $dim3->setCode('P0000');
    //$purchaseTransactionLine2->setDim3($dim3);                                                                                                                // object|null                  If line type = total empty. If line type = detail the project or asset or empty. If line type = vat the project or asset or empty.
    //$purchaseTransactionLine2->setDim3(\PhpTwinfield\Project::fromCode('P0000'));                                                                             // string|null
    //$purchaseTransactionLine2->setFreeChar('A');                                                                                                              // string|null                  Free character field. If line type is total and filled with N the purchase invoice is excluded from payment runs done in Twinfield.
    $performanceCountry = new \PhpTwinfield\Country;
    $performanceCountry->setCode('NL');
    //$purchaseTransactionLine2->setPerformanceCountry($performanceCountry);                                                                                    // Country|null                 Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The ISO country codes are used. If not added to the request, by default the country code of the customer will be taken.
    //$purchaseTransactionLine2->setPerformanceCountry(\PhpTwinfield\Country::fromCode('NL'));                                                                  // string|null
    $performanceDate = \DateTime::createFromFormat('d-m-Y', '01-09-2019');
    //$purchaseTransactionLine2->setPerformanceDate($performanceDate);                                                                                          // DateTimeInterface|null       Only if line type is detail or vat. Mandatory in case of an ICT VAT code but only if performancetype is services.
    //$purchaseTransactionLine2->setPerformanceDate(Util::parseDate("20190901"));                                                                               // string|null
    //$purchaseTransactionLine2->setPerformanceType(\PhpTwinfield\Enums\PerformanceType::SERVICES());                                                           // PerformanceType|null         Only if line type is detail or vat. Mandatory in case of an ICT VAT code. The performance type.
    //$purchaseTransactionLine2->setPerformanceVatNumber('NL1234567890B01');                                                                                    // string|null                  Only if line type is detail or vat. Mandatory in case of an ICT VAT code. If not added to the request, by default the VAT number of the customer will be taken.
    //$purchaseTransactionLine2->setRepValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Amount in the reporting currency.
    //$purchaseTransactionLine2->setRate(1);                                                                                                                    // float|null                   The exchange rate used for the calculation of the base amount.
    //$purchaseTransactionLine2->setRepRate(1);                                                                                                                 // float|null                   The exchange rate used for the calculation of the reporting amount.
    //$purchaseTransactionLine2->setVatBaseValue(\Money\Money::EUR(-10000));                                                                                    // Money|null                   Only if line type is detail. VAT amount in base currency.
    $vatCode = new \PhpTwinfield\VatCode;
    $vatCode->setCode('VN');
    //$purchaseTransactionLine2->setVatCode($vatCode);                                                                                                          // VatCode|null                 Only if line type is detail or vat. VAT code.
    //$purchaseTransactionLine2->setVatCode(\PhpTwinfield\VatCode::fromCode('VN'));                                                                             // string|null
    //$purchaseTransactionLine2->setVatRepValue(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is detail. VAT amount in reporting currency.
    //$purchaseTransactionLine2->setVatValue(\Money\Money::EUR(-10000));                                                                                        // Money|null                   Only if line type is detail. VAT amount in the currency of the purchase transaction.

    $purchaseTransaction->addLine($purchaseTransactionLine2);

    //$purchaseTransactionLine3 = new \PhpTwinfield\PurchaseTransactionLine;
    //$purchaseTransactionLine3->setLineType(\PhpTwinfield\Enums\LineType::VAT());                                                                              // LineType|null                Line type.
    //$purchaseTransactionLine3->setBaseline(1);                                                                                                                // int|null                     Only if line type is vat. The value of the baseline tag is a reference to the line ID of the VAT rate.
    //$purchaseTransactionLine3->setVatBaseTurnover(\Money\Money::EUR(-10000));                                                                                 // Money|null                   Only if line type is vat. Amount on which VAT was calculated in base currency.
    //$purchaseTransactionLine3->setVatRepTurnover(\Money\Money::EUR(-10000));                                                                                  // Money|null                   Only if line type is vat. Amount on which VAT was calculated in reporting currency.
    //$purchaseTransactionLine3->setVatTurnover(\Money\Money::EUR(-10000));                                                                                     // Money|null                   Only if line type is vat. Amount on which VAT was calculated in the currency of the purchase transaction.

    try {
        $purchaseTransactionNew = $transactionApiConnector->send($purchaseTransaction);
    } catch (ResponseException $e) {
        $purchaseTransactionNew = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($purchaseTransactionNew);
    echo "</pre>";

    echo "Result of creation process: {$purchaseTransactionNew->getResult()}<br />";
    echo "Number of new PurchaseTransaction: {$purchaseTransactionNew->getNumber()}<br />";
}

// Delete a BrankTransaction based off the passed in office, code, number and given reason
if ($executeDelete) {
    $bookingReference = new \PhpTwinfield\BookingReference($office, 'INK', 201900004);

    try {
        $purchaseTransactionDeleted = $transactionApiConnector->delete($bookingReference, 'Example reason');
    } catch (ResponseException $e) {
        $purchaseTransactionDeleted = $e->getReturnedObject();
    }

    echo "<pre>";
    print_r($purchaseTransactionDeleted);
    echo "</pre>";
}