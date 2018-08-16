<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Customer;
use PhpTwinfield\CustomerAddress;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\CustomerCreditManagement;
use PhpTwinfield\DomDocuments\CustomersDocument;
use PhpTwinfield\Office;
use PHPUnit\Framework\TestCase;

class CustomersDocumentUnitTest extends TestCase
{
    /**
     * @var CustomersDocument
     */
    protected $document;

    protected function setUp()
    {
        parent::setUp();

        $this->document = new CustomersDocument();
    }

    public function testXmlIsCreatedPerSpec()
    {
        $customer = new Customer();
        $customer->setCode('D123456');
        $customer->setName('Chuck Norris');
        $customer->setWebsite('http://example.org');
        $customer->setOffice(Office::fromCode("DEV-10000"));
        $customer->setStatus('active');

        $customer->setDueDays(1);
        $customer->setPayAvailable(true);
        $customer->setPayCode('pay-code');
        $customer->setVatCode('vat-code');
        $customer->setEBilling(true);
        $customer->setEBillMail('ebillingmail@mail.com');

        $customer->setCreditManagement(
            (new CustomerCreditManagement())
                ->setResponsibleUser('responsible-user')
                ->setBaseCreditLimit(50)
                ->setSendReminder(true)
                ->setReminderEmail('reminderemail@mail.com')
                ->setBlocked(false)
                ->setFreeText1('free1')
                ->setFreeText2('free2')
                ->setComment('comment    comment')
        );
        $customer->addAddress(
            (new CustomerAddress())
                ->setDefault(true)
                ->setType('invoice')
                ->setName('My Address')
                ->setContact('My Contact')
                ->setCountry('nl')
                ->setCity('city')
                ->setPostcode('postal code')
                ->setTelephone('phone number')
                ->setFax('fax number')
                ->setEmail('email@mail.com')
                ->setField1('field 1')
                ->setField2('field 2')
                ->setField3('field 3')
                ->setField4('field 4')
                ->setField5('field 5')
                ->setField6('field 6')
        );
        $customer->addBank(
            (new CustomerBank())
                ->setDefault(true)
                ->setAscription('ascriptor')
                ->setAccountnumber('account number')
                ->setBankname('bank name')
                ->setBiccode('bic code')
                ->setCity('city')
                ->setCountry('nl')
                ->setIban('iban')
                ->setNatbiccode('nat-bic')
                ->setPostcode('postcode')
                ->setState('state')
                ->setAddressField2('address 2')
                ->setAddressField3('address 3')
        );

        $this->document->addCustomer($customer);

        $customer = new Customer();
        $customer->setCode('D654321');
        $customer->setName('Nuck CHorris');
        $customer->setWebsite('http://example.org');
        $customer->setOffice(Office::fromCode("DEV-00001"));
        $customer->setStatus('deleted');

        $this->document->addCustomer($customer);

        $this->assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0"?>
<dimensions>
	<dimension status="active">
	    <code>D123456</code>
	    <name>Chuck Norris</name>
	    <type>DEB</type>
	    <website>http://example.org</website>
	    <office>DEV-10000</office>
	    <financials>
	        <duedays>1</duedays>
	        <payavailable>true</payavailable>
	        <paycode>pay-code</paycode>
	        <vatcode>vat-code</vatcode>
	        <ebilling>true</ebilling>
	        <ebillmail>ebillingmail@mail.com</ebillmail>
        </financials>
        <creditmanagement>
            <responsibleuser>responsible-user</responsibleuser>
            <basecreditlimit>50</basecreditlimit>
            <sendreminder>true</sendreminder>
            <reminderemail>reminderemail@mail.com</reminderemail>
            <blocked>false</blocked>
            <freetext1>free1</freetext1>
            <freetext2>free2</freetext2>
            <comment>comment    comment</comment>
        </creditmanagement>
        <addresses>
            <address default="true" type="invoice">
                <name>My Address</name>
                <contact>My Contact</contact>
                <country>nl</country>
                <city>city</city>
                <postcode>postal code</postcode>
                <telephone>phone number</telephone>
                <telefax>fax number</telefax>
                <email>email@mail.com</email>
                <field1>field 1</field1>
                <field2>field 2</field2>
                <field3>field 3</field3>
                <field4>field 4</field4>
                <field5>field 5</field5>
                <field6>field 6</field6>
            </address>
        </addresses>
        <banks>
            <bank default="true">
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
                <address>
                    <field2>address 2</field2>                
                    <field3>address 3</field3>                
                </address>
            </bank>
        </banks>
    </dimension>
    <dimension status="deleted">
        <code>D654321</code>
        <name>Nuck Chorris</name>
        <type>DEB</type>
        <website>http://example.org</website>
        <office>DEV-00001</office>
    </dimension>
</dimensions>
XML
            ,
            $this->document->saveXML()
        );
    }
}