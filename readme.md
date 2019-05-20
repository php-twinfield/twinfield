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
For breaking changes since 2.6.2 see [Breaking Changes since 2.6.2](breaking262.md).

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
| [Electronic Bank Statements](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankStatements)       |                    | :white_check_mark: |                    |                    |                    |                                                          |
| [Fixed Assets](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/FixedAssets)                             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Fixed Asset](examples/FixedAsset.php)                   |
| [General Ledger Accounts](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/BalanceSheets)                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [General Ledger Account](examples/GeneralLedger.php)     |
| [Matching](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Miscellaneous/Matching)                              |                    |                    | :white_check_mark: |                    | :white_check_mark: |                                                          |
| [Offices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Offices)                                      | :white_check_mark: | :white_check_mark: |                    |                    | :white_check_mark: | [Office](examples/Office.php)                            |
| Paycodes                                                                                                                          |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Paycode](examples/PayCode.php)                          |
| [Projects](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Projects)                                    | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Project](examples/Project.php)                          |
| [Rates](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Rates)                                          | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Rate](examples/Rate.php)                                |
| [Sales Invoices](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                                 | :white_check_mark: | :white_check_mark: | :white_check_mark: |                    | :white_check_mark: | [Sales Invoice](examples/Invoice.php)                    |
| [Sales Invoice Types](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesInvoices)                            |                    | :white_check_mark: |                    |                    | :white_check_mark: | [Sales Invoice Type](examples/InvoiceType.php)           |
| [Suppliers](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Masters/Suppliers)                                  | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | [Supplier](examples/Supplier.php)                        |
|                                                                                                                                   |                    |                    |                    |                    |                    |                                                          |
| <b>Transactions</b>                                                                                                               |                    |                    |                    |                    |                    |                                                          |
[Bank Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/BankTransactions)                | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                                                          |
[Cash Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/CashTransactions)                | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                                                          |
[Journal Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/Transactions/JournalTransactions)          | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                                                          |
[Purchase Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/PurchaseTransactions)                     | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                                                          |
[Sale Transactions](https://c3.twinfield.com/webservices/documentation/#/ApiReference/SalesTransactions)                            | :white_check_mark: |                    | :white_check_mark: | :white_check_mark: | :white_check_mark: |                                                          |
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
