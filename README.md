# TwinfieldPHP
A PHP library for Twinfield Integration. Developed by [Remco Tolsma](http://remcotolsma.nl) and [Leon Rowland](http://leon.rowland.nl) from [Pronamic](http://pronamic.nl)

---

## Autoloading

The classes follow the PSR0 naming convention.


## Usage

Currently the Library supports the following components from Twinfield

* Customer
* Invoice

### General Usage Information
Components will have Factories to simplify the request and send process of Twinfield.
Each factory will require just the \Pronamic\Twinfield\Secure\Config() class with
the filled in details.

An example of the usage of the Configuration class.

```php
$config = new \Pronamic\Twinfield\Secure\Config();
$config->setCredentials('Username', 'Password', 'Organization', 'Office');
```

Now, to the current modules

In the following example, we will use the Customer component as showcase. Although 
this will be the method for all components ( including Invoice currently )

Typically it is as follows, if using the Factories

* Add/Edit: Make Object, Make Factory, Give object in Submit method of related factory.
* Retrieve: Make Factory, Supply all required params to respective listAll and get methods

#### Add/Edit

Make your Customer object

```php
$customer = new \Pronamic\Twinfield\Customer\Customer();
$customer
	->setID(10666)
	->setName('Leon Rowland')
	->setType('DEB')
	->setWebsite('http://leon.rowland.nl')
	->setEBilling(true)
	->setEBillMail('leon@rowland.nl')
	->setVatCode('VL')
	->setDueDays(10)
	->setCocNumber('12341234');
```

Customers can have addresses associated with them

```php
$customerAddress = new \Pronamic\Twinfield\Customer\CustomerAddress();
$customerAddress
	->setDefault(false)
	->setType('invoice')
	->setField1('Testing field 1')
	->setField2('Testing field 2')
	->setField3('Testing field 3')
	->setPostcode('1212 AB')
	->setCity('TestCity')
	->setCountry('NL')
	->setTelephone('010-12345')
	->setFax('010-1234')
	->setEmail('test@email.com');
```

Assign that address to the customer

```php
$customer->addAddress($customer_address);
```

Now lets submit it!

```php
// config at the ready

$customerFactory = new \Pronamic\Twinfield\Customer\CustomerFactory($config);

if($customerFactory->send($customer)){
	// then it was successful.

	// you can get the responded XML document, and even turn that back into
	// a new customer object
	$successfulCustomer = \Pronamic\Twinfield\Customer\Mapper\CustomerMapper::map($customerFactory->getResponse()->getResponseDocument()->saveXML());
}
```

#### Retrieve/Request

You can get all customers or get a single one currently.

```php

// config at the ready
$customerFactory = new \Pronamic\Twinfield\Customer\CustomerFactory($config);

$customers = $customerFactory->listAll();
```

At the moment, listAll will return an array of just name and short name.

```php

$customer = $customerFactory->get('customerCode', 'office[optional]');
```

The response from get() will be a \Pronamic\Twinfield\Customer\Customer object.


#### Notes

Advanced documentation coming soon. Detailing usage without the Factory class. Giving you more control
with the response and data as well as more indepth examples and usage recommendations.


## Contribute

You can contribute to the development of this project. Try and keep to the way of doing things as
the other 2 components have implemented.

A large requirement is to maintain backwards compatibility so if you have any plans for large
restructure or alteration please bring up in an issue first.

Other than that, fork away!

## Authors

* [Pronamic](http://pronamic.nl)
* [Remco Tolsma](http://remcotolsma.nl)
* [Leon Rowland](http://leon.rowland.nl)

## License

Copyright 2009-2011 Pronamic.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
