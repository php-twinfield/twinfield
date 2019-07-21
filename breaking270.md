# Breaking changes since 2.7.0
Release ... added multiple new resources, but also broke several existing ones.

Breaking changes are present in:
- Bank Transactions (Separate API connector completely removed and functionality moved to general Transactions API Connector)
- Articles
- Customers
- Sales Invoices
- Suppliers
- Transactions
- Vat Codes

Except for Bank Transactions, the breaking changes are primarily due to the fact that most fields and methods are now properly type cast.

This means (among others) that all booleans are now type cast as bool, all monetary values are now Money\Money instances and several fields now use Enums instead of strings.

Another big change is that retrieved fields that are instances of another entity in Twinfield and this library are now retrieved and set in the same way as was already done with Office or Customer codes.
So for example a VAT code retrieved from or set to a PhpTwinfield\Article will now become an instance of PhpTwinfield\VatCode with its $code set to "VH" instead of begin just a string with the value "VH".

See the tables per class below for breaking changes and suggestions for fixes/replacement methods.

## Bank Transactions

-TODO

## Articles

-TODO

### Article Lines

-TODO

## Customers

-TODO

## Sales Invoices

-TODO

## Suppliers

-TODO

## Cash/Journal/Purchase/Sale Transactions

-TODO

## VAT

-TODO