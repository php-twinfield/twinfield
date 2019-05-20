# Breaking changes since 2.6.2
Release 3.0 added multiple new resources, but also broke several existing ones.

Breaking changes are present in:
- Bank Transactions (Separate API connector completely removed and functionality moved to general Transactions API Connector)
- Articles
- Customers
- Sales Invoices
- Suppliers
- Vat Codes

Except for Bank Transactions, the breaking changes are primarily due to the fact that most fields and methods are now properly type cast.

This means (among others) that all booleans are now type cast as bool, all monetary values are now Money\Money instances and several fields now use Enums instead of strings.

Another big change is that retrieved fields that are instances of another entity in Twinfield and this library are now retrieved and set in the same way as was already done with Office or Customer codes.
So for example a VAT code retrieved from or set to a PhpTwinfield\Article will now become an instance of PhpTwinfield\VatCode with its $code set to "VH" instead of begin just a string with the value "VH".

Most of these changed methods have gained ToString/FromString or ToFloat/FromFloat methods that will ease the transition.

See the tables per class below for breaking changes and suggestions for fixes/replacement methods.

## Bank Transactions

| Class/Method                                                               | Change                                | Replaced by                           | Old Usage                                                                       | New Usage                                                                                               |
| -------------------------------------------------------------------------- | :-----------------------------------: | :-----------------------------------: | :-----------------------------------------------------------------------------: | :-----------------------------------------------------------------------------------------------------: |
| ApiConnectors\BankTransactionApiConnector                                  | Class removed                         | ApiConnectors\TransactionApiConnector | $bankTransactionApiConnector = ApiConnectors\BankTransactionApiConnector;       | $bankTransactionApiConnector = ApiConnectors\BankTransactionApiConnector;                               |
| Transactions\BankTransactionLine\Base                                      | Class removed                         | BankTransactionLine                   | N.A.                                                                            | N.A.                                                                                                    |
| Transactions\BankTransactionLine\Detail                                    | Class removed                         | BankTransactionLine                   | $bankTransactionLineDetail = New Transactions\BankTransactionLine\Detail;       | $bankTransactionLineDetail = New BankTransactionLine; $bankTransactionLine->setType(LineType::DETAIL()) |
| Transactions\BankTransactionLine\Total                                     | Class removed                         | BankTransactionLine                   | $bankTransactionLineTotal = New Transactions\BankTransactionLine\Total;         | $bankTransactionLineTotal = New BankTransactionLine; $bankTransactionLine->setType(LineType::TOTAL())   |
| Transactions\BankTransactionLine\Vat                                       | Class removed                         | BankTransactionLine                   | $bankTransactionLineVat = New Transactions\BankTransactionLine\Vat;             | $bankTransactionLineVat = New BankTransactionLine; $bankTransactionLine->setType(LineType::VAT())       |
| Transactions\BankTransactionLine\Detail::setAccount                        | Method removed                        | BankTransactionLine::setDim1          | $bankTransactionLineDetail->setAccount('4010');                                 | $bankTransactionLineDetail->setDim1FromString('4010');                                                  |
| Transactions\BankTransactionLine\Detail::setCustomerOrSupplierOrCostCenter | Method removed                        | BankTransactionLine::setDim2          | $bankTransactionLineDetail->setCustomerOrSupplierOrCostCenter('2010');          | $bankTransactionLineDetail->setDim2FromString('2010');                                                  |
| Transactions\BankTransactionLine\Detail::setProjectOrAsset                 | Method removed                        | BankTransactionLine::setDim3          | $bankTransactionLineDetail->setProjectOrAsset('2010');                          | $bankTransactionLineDetail->setDim3FromString('P000');                                                  |
| Transactions\BankTransactionLine\Total::setBankBalanceAccount              | Method removed                        | BankTransactionLine::setDim1          | $bankTransactionLineTotal->setBankBalanceAccount('5010');                       | $bankTransactionLineTotal->setDim1FromString('5010');                                                   |
| Transactions\BankTransactionLine\Vat::setVatBalanceAccount                 | Method removed                        | BankTransactionLine::setDim1          | $bankTransactionLineVat->setVatBalanceAccount('5050');                          | $bankTransactionLineVat->setDim1FromString('5050');                                                     |

## Articles

| Class/Method                       | Change                                                                 | Replaced by                           | Old Usage                                  | New Usage                                                                                                              |
| ---------------------------------- | :--------------------------------------------------------------------: | :-----------------------------------: | :----------------------------------------: | :--------------------------------------------------------------------------------------------------------------------: |
| Article::setStatus                 | setStatus now expects Enums\Status instead of string                   | Article::setStatusFromString          | $article->setStatus('active');             | $article->setStatusFromString('active'); OR $article->setStatus(Enums\Status::ACTIVE())                                |
| Article::setType                   | setType now expects Enums\ArticleType instead of string                | Article::setTypeFromString            | $article->setType('normal');               | $article->setTypeFromString('normal'); OR $article->setType(Enums\ArticleType::NORMAL())                               |
| Article::setPerformanceType        | setPerformanceType now expects Enums\PerformanceType instead of string | Article::setPerformanceTypeFromString | $article->setPerformanceType('services');  | $article->setPerformanceTypeFromString('services'); OR $article->setPerformanceType(Enums\PerformanceType::SERVICES()) |
| Article::getAllowDiscountorPremium | Properly camelCased                                                    | Article::getAllowDiscountOrPremium    | $article->getAllowDiscountorPremium(true); | $article->getAllowDiscountOrPremium(true);                                                                             |
| Article::setAllowDiscountorPremium | Properly camelCased                                                    | Article::setAllowDiscountOrPremium    | $article->setAllowDiscountorPremium(true); | $article->setAllowDiscountOrPremium(true);                                                                             |

### Article Lines

| Class/Method                   | Change                                                      | Replaced by                             | Old Usage                                            | New Usage                                                                                                                                                           |
| ------------------------------ | :---------------------------------------------------------: | :-------------------------------------: | :--------------------------------------------------: | :-----------------------------------------------------------------------------------------------------------------------------------------------------------------: |
| ArticleLine::setStatus         | setStatus now expects Enums\Status instead of string        | ArticleLine::setStatusFromString        | $articleLine->setStatus('active');                   | $articleLine->setStatusFromString('active'); OR $articleLine->setStatus(Enums\Status::ACTIVE())                                                                     |
| ArticleLine::getUnitsPriceInc  | getUnitsPriceInc now returns Money instead of string/float  | ArticleLine::getUnitsPriceIncToFloat    | $unitsPriceInc = $articleLine->getUnitsPriceInc();   | $unitsPriceInc = $articleLine->getUnitsPriceIncToFloat(); OR $unitsPriceInc = \Money\Formatter\DecimalMoneyFormatter::format($articleLine->getUnitsPriceInc());     |
| ArticleLine::setUnitsPriceInc  | setUnitsPriceInc now expects Money instead of string/float  | ArticleLine::setUnitsPriceIncFromFloat  | $articleLine->setUnitsPriceInc(100.50);              | $articleLine->setUnitsPriceIncFromFloat(100.50); OR $articleLine->setUnitsPriceInc(New \Money\Money(10050), new \Money\Currency('EUR'));                            |
| ArticleLine::getUnitsPriceExcl | getUnitsPriceExcl now returns Money instead of string/float | ArticleLine::getUnitsPriceExclToFloat   | $unitsPriceExcl = $articleLine->getUnitsPriceExcl(); | $unitsPriceExcl = $articleLine->getUnitsPriceExclToFloat(); OR $unitsPriceExcl = \Money\Formatter\DecimalMoneyFormatter::format($articleLine->getUnitsPriceExcl()); |
| ArticleLine::setUnitsPriceExcl | setUnitsPriceExcl now expects Money instead of string/float | ArticleLine::setUnitsPriceExclFromFloat | $articleLine->setUnitsPriceExcl(80.75);              | $articleLine->setUnitsPriceExclFromFloat(80.75); OR $articleLine->setUnitsPriceExcl(New \Money\Money(8075), new \Money\Currency('EUR'));                            |
| ArticleLine::getFreeText1      | getFreeText1 now returns GeneralLedger instead of string    | ArticleLine::getFreeText1ToString       | $dim1 = $articleLine->getFreeText1();                | $dim1 = $articleLine->getFreeText1ToString(); OR $dim1 = $articleLine->getFreeText1()->getCode();                                                                   |
| ArticleLine::setFreeText1      | setFreeText1 now expects GeneralLedger instead of string    | ArticleLine::setFreeText1FromString     | $articleLine->setFreeText1('4050');                  | $articleLine->setFreeText1FromString('4050'); OR $dim1 = new GeneralLedger; $dim1->setCode('4050'); $articleLine->setFreeText1($dim1);                              |

## Customers

## Sales Invoices

## Suppliers

## Cash/Journal/Purchase/Sale Transactions

BaseTransaction getCurrency now returns PhpTwinfield\Currency instead of Money\Currency
BaseTransaction setCurrency now expects PhpTwinfield\Currency instead of Money\Currency

BaseTransactionLine setId -> setID

BankTransaction getCloseValue Properly camelCased
BankTransaction setCloseValue Properly camelCased

BankTransaction getStartValue Properly camelCased
BankTransaction setStartValue Properly camelCased

CashTransaction getCloseValue Properly camelCased
CashTransaction setCloseValue Properly camelCased

CashTransaction getStartValue Properly camelCased
CashTransaction setStartValue Properly camelCased

## VAT
