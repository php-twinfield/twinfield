<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Supplier;
use PhpTwinfield\SupplierAddress;
use PhpTwinfield\SupplierBank;
use PhpTwinfield\SupplierFinancials;
use PhpTwinfield\DomDocuments\SuppliersDocument;
use PhpTwinfield\Office;
use PHPUnit\Framework\TestCase;

class SupplierDocumentUnitTest extends TestCase
{
    /**
     * @var SuppliersDocument
     */
    protected $document;

    protected function setUp()
    {
        parent::setUp();

        $this->document = new SuppliersDocument();
    }

    public function testXmlIsCreatedPerSpec()
    {
        $supplier = new Supplier();
        $supplier->setCode('D123456');
        $supplier->setName('Chuck Norris');
        $supplier->setWebsite('http://example.org');
        $supplier->setOffice(Office::fromCode("DEV-10000"));
        $supplier->setStatus(\PhpTwinfield\Enums\Status::ACTIVE());

        $financials = new SupplierFinancials();
        $financials->setDueDays(1);
        $financials->setPayAvailable(true);
        $financials->setPayCode(\PhpTwinfield\PayCode::fromCode('pay-code'));
        $financials->setVatCode(\PhpTwinfield\VatCode::fromCode('vat-code'));
        $supplier->setFinancials($financials);

        $supplier->addAddress(
            (new SupplierAddress())
                ->setDefault(true)
                ->setType(\PhpTwinfield\Enums\AddressType::INVOICE())
                ->setName('My Address')
                ->setCountry(\PhpTwinfield\Country::fromCode('nl'))
                ->setCity('city')
                ->setPostcode('postal code')
                ->setTelephone('phone number')
                ->setTelefax('fax number')
                ->setEmail('email@mail.com')
                ->setField1('field 1')
                ->setField2('field 2')
                ->setField3('field 3')
                ->setField4('field 4')
                ->setField5('field 5')
                ->setField6('field 6')
        );

        $supplier->addBank(
            (new SupplierBank())
                ->setDefault(true)
                ->setAddressField2('address 2')
                ->setAddressField3('address 3')
                ->setAscription('ascriptor')
                ->setAccountNumber('account number')
                ->setBankName('bank name')
                ->setBicCode('bic code')
                ->setCity('city')
                ->setCountry(\PhpTwinfield\Country::fromCode('nl'))
                ->setIban('iban')
                ->setNatBicCode('nat-bic')
                ->setPostcode('postcode')
                ->setState('state')
        );

        $this->document->addSupplier($supplier);

        $supplier = new Supplier();
        $supplier->setCode('D654321');
        $supplier->setName('Nuck Chorris');
        $supplier->setWebsite('http://example.org');
        $supplier->setOffice(Office::fromCode("DEV-00001"));
        $supplier->setStatus(\PhpTwinfield\Enums\Status::DELETED());

        $this->document->addSupplier($supplier);

        $this->assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0"?>
<dimensions>
	<dimension status="active">
	    <beginperiod>0</beginperiod>
	    <beginyear>0</beginyear>
	    <code>D123456</code>
	    <endperiod>0</endperiod>
	    <endyear>0</endyear>
	    <name>Chuck Norris</name>
	    <office>DEV-10000</office>
	    <shortname/>
	    <type>CRD</type>
	    <website>http://example.org</website>
	    <financials>
	        <duedays>1</duedays>
	        <meansofpayment/>
	        <payavailable>true</payavailable>
	        <paycode>pay-code</paycode>
	        <relationsreference/>
            <substitutewith/>
	        <vatcode>vat-code</vatcode>
        </financials>
        <addresses>
            <address default="true" type="invoice">
                <city>city</city>
                <country>nl</country>
                <email>email@mail.com</email>
                <field1>field 1</field1>
                <field2>field 2</field2>
                <field3>field 3</field3>
                <field4>field 4</field4>
                <field5>field 5</field5>
                <field6>field 6</field6>
                <name>My Address</name>
                <postcode>postal code</postcode>
                <telefax>fax number</telefax>
                <telephone>phone number</telephone>
            </address>
        </addresses>
        <banks>
            <bank blocked="false" default="true">
                <address>
                    <field2>address 2</field2>
                    <field3>address 3</field3>
                </address>
                <ascription>ascriptor</ascription>
                <accountnumber>account number</accountnumber>
                <bankname>bank name</bankname>
                <biccode>bic code</biccode>
                <city>city</city>
                <country>nl</country>
                <iban>iban</iban>
                <natbiccode>nat-bic</natbiccode>
                <postcode>postcode</postcode>
                <state>state</state>
            </bank>
        </banks>
        <remittanceadvice>
            <sendmail/>
            <sendtype/>
        </remittanceadvice>
        <paymentconditions>
            <paymentcondition>
                <discountdays/>
                <discountpercentage/>
            </paymentcondition>
        </paymentconditions>
        <blockedaccountpaymentconditions>
            <includevat/>
            <percentage/>
        </blockedaccountpaymentconditions>
    </dimension>
    <dimension status="deleted">
	    <beginperiod>0</beginperiod>
	    <beginyear>0</beginyear>
	    <code>D654321</code>
	    <endperiod>0</endperiod>
	    <endyear>0</endyear>
	    <name>Nuck Chorris</name>
	    <office>DEV-00001</office>
	    <shortname/>
	    <type>CRD</type>
	    <website>http://example.org</website>
	    <financials>
            <duedays>30</duedays>
            <meansofpayment/>
            <payavailable>false</payavailable>
            <paycode/>
            <relationsreference/>
            <substitutewith/>
            <vatcode/>
	    </financials>
	    <remittanceadvice>
            <sendmail/>
            <sendtype/>
	    </remittanceadvice>
	    <paymentconditions>
            <paymentcondition>
                <discountdays/>
                <discountpercentage/>
            </paymentcondition>
	    </paymentconditions>
        <blockedaccountpaymentconditions>
            <includevat/>
            <percentage/>
        </blockedaccountpaymentconditions>
    </dimension>
</dimensions>
XML
            ,
            $this->document->saveXML()
        );
    }
}
