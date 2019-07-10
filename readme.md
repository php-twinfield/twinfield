# Twinfield  [![Build Status](https://travis-ci.org/php-twinfield/twinfield.svg?branch=master)](http://travis-ci.org/php-twinfield/twinfield)
A PHP library for Twinfield Integration.
Use the Twinfield SOAP Services to have your PHP application communicate directly with your Twinfield account.

**:warning: Note that this libary is *not* created or maintained by Twinfield. You can only get support on the code in this library here. For any questions related to your Twinfield administration or how to do certain things with the Twinfield API, contact your Twinfield account manager.**

## Installation

Install this Twinfield PHP library with Composer:

```bash
composer require 'php-twinfield/twinfield:^2.0'
```

Considering session login is deprecated and OAuth 2 is the preferred login method you should also install PHP League's OAuth 2.0 Client

```bash
composer require 'league/oauth2-client'
```

## Usage

See [Usage](usage.md).
For breaking changes since 2.7.0 see [Breaking Changes since 2.7.0](breaking270.md).

### ApiConnector Configuration

The ApiConnector has a constructor second parameter that can be used to configure some aspects of its operation.

The ApiOptions has the following methods signature:

```php
/**
 * This will allow you to enfornce the messages or the number of max retries.
 * Passing null you will use the default values.
 */
public function __construct(?array $messages = null, ?int $maxRetries = null);
/**
 * This will allow you to get all the exception messages
 */
public function getRetriableExceptionMessages(): array
/**
 * This will allow you to replace the exception messages that should be retried
 */
public function setRetriableExceptionMessages(array $retriableExceptionMessages): ApiOptions
/**
 * This will allow you to add new messages to the array of exception messages
 */
public function addMessages(array $messages): ApiOptions
/**
 * This will allow you to get the number of max retries
 */
public function getMaxRetries(): int
/**
 * This will allow you to set the number of max retries
 */
public function setMaxRetries(int $maxRetries): ApiOptions
```



:exclamation: All the *get* methods will return a new instance with the configuration you changed.

#### Configuration Examples

Below are some examples on how to use the configuration object

```php
$connector = new BrowseDataApiConnector(
    $connection,
    new ApiOptions(
        [
            "SSL: Connection reset by peer",
            "Bad Gateway"
        ], 
        3
    )
);
```

The example below will look for the defaul messages plus the "Bad Gateway" message.

```php
$options = new ApiOptions(
    null, 
    3
);
$connector = new BrowseDataApiConnector(
    $connection,
    $options->addMessages(["Bad Gateway"])
);
```

#### Configuration default values

| Attribute                    | Default Value                                                | Description                                                  |
| ---------------------------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| Max retries                  | 3                                                            | The number of retries that should happen before throwing an error. |
| Retriable exception messages | [ <br />"SSL: Connection reset by peer",     <br />"Your logon credentials are not valid anymore. Try to log on again." <br />] | The exception messages that should be match in order to retry automatically. |

### Supported resources

Not all resources from the Twinfield API are currently implemented. Feel free to create a pull request when you need
support for another resource.

| Component                                                                                                                         | get()              | listAll()          | send()             | delete()           |  Mapper            |  Example                                                 |
| ----------------------------------------------------------------------------------------------------------------------------------| :----------------: | :----------------: | :----------------: | :----------------: | :----------------: | :----------------:                                       |
| [Activities](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Activities)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Activity](examples/Activity.php)                        |
| [Articles](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Articles)                                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Article](examples/Article.php)                          |
| [Asset Methods](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/AssetMethods)                           | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Asset Method](examples/AssetMethod.php)                 |
| [Browse Data](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Request/BrowseData)                               | :white_check_mark: |                    |                    |                    | :white_check_mark: | [Browse Data](examples/BrowseData.php)                   |
| Cash and Bank Books                                                                                                               |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Cash and Bank Book](examples/CashBankBook.php)          |
| [Cost Centers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/CostCenters)                             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Cost Center](examples/CostCenter.php)                   |
| Countries                                                                                                                         |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Country](examples/Country.php)                          |
| [Currencies](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Currencies)                                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Currency](examples/Currency.php)                        |
| [Customers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Customers)                                  | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Customer](examples/Customer.php)                        |
| [Dimension Groups](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionGroups)                     | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Dimension Group](examples/DimensionGroup.php)           |
| [Dimension Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/DimensionTypes)                       | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: | [Dimension Type](examples/DimensionType.php)             |
| [Electronic Bank Statements](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements)       |                    |                    | :white_check_mark: |                    |                    |                                                          |
| [Fixed Assets](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets)                             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Fixed Asset](examples/FixedAsset.php)                   |
| [General Ledger Accounts](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets)                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [General Ledger Account](examples/GeneralLedger.php)     |
| [Matching](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching)                              |                    |                    | :white_check_mark: |                    | :white_check_mark: |                                                          |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                                      | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: | [Office](examples/Office.php)                            |
| Paycodes                                                                                                                          |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Paycode](examples/PayCode.php)                          |
| [Projects](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects)                                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Project](examples/Project.php)                          |
| [Rates](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates)                                          | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Rate](examples/Rate.php)                                |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                                 | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: | [Sales Invoice](examples/Invoice.php)                    |
| Sales Invoice Types                                                                                                               |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Sales Invoice Type](examples/InvoiceType.php)           |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                                  | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Supplier](examples/Supplier.php)                        |
|                                                                                                                                   |                    |                    |                    |                    |                    |                                                          |
| <b>Transactions</b>                                                                                                               |                    |                    |                    |                    |                    |                                                          |
[Bank Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions)                | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Bank Transaction](examples/BankTransaction.php)         |
[Cash Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions)                | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Cash Transaction](examples/CashTransaction.php)         |
[Journal Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions)          | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Journal Transaction](examples/JournalTransaction.php)   |
[Purchase Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions)                     | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Purchase Transaction](examples/PurchaseTransaction.php) |
[Sales Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions)                            | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Sales Transaction](examples/SalesTransaction.php)       |
|                                                                                                                                   |                    |                    |                    |                    |                    |                                                          |
| [Users](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Users)                                          | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: | [User](examples/User.php)                                |
| User Roles                                                                                                                        |                    | :white_check_mark: |                    |                    | :white_check_mark: | [User Role](examples/UserRole.php)                       |
| [VAT](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/VAT)                                              | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [VAT](examples/VatCode.php)                              |
| VAT Groups                                                                                                                        |                    | :white_check_mark: |                    |                    | :white_check_mark: | [VAT Group](examples/VatGroup.php)                       |
| VAT Group Countries                                                                                                               |                    | :white_check_mark: |                    |                    | :white_check_mark: | [VAT Group Country](examples/VatGroupCountry.php)        |

## Links

* [Twinfield API Documentation site](https://c3.twinfield.com/webservices/documentation/)


## Authors

* [Pronamic](https://www.pronamic.nl/)
* [Mollie](https://www.mollie.com/)
* [Remco Tolsma](https://www.remcotolsma.nl/)
* [Emile Bons](http://www.emilebons.nl/)
* [Alex Jeensma](http://vontis.nl/)
