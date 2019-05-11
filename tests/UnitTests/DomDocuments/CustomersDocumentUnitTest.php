<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Customer;
use PhpTwinfield\CustomerAddress;
use PhpTwinfield\CustomerBank;
use PhpTwinfield\CustomerCreditManagement;
use PhpTwinfield\CustomerFinancials;
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
        $customer->setStatusFromString('active');

	$financials = new CustomerFinancials();
        $financials->setDueDays(1);
        $financials->setPayAvailable(true);
        $financials->setPayCodeFromCode('pay-code');
        $financials->setVatCodeFromCode('vat-code');
        $financials->setEBilling(true);
        $financials->setEBillMail('ebillingmail@mail.com');
	$customer->setFinancials($financials);

        $customer->setCreditManagement(
            (new CustomerCreditManagement())
                ->setResponsibleUserFromCode('responsible-user')
                ->setBaseCreditLimitFromFloat(50)
                ->setSendReminderFromString('true')
                ->setReminderEmail('reminderemail@mail.com')
                ->setBlocked(false)
                ->setFreeText1FromString('true')
                ->setFreeText2('free2')
                ->setComment('comment    comment')
        );
        $customer->addAddress(
            (new CustomerAddress())
                ->setDefault(true)
                ->setTypeFromString('invoice')
                ->setName('My Address')
                ->setCountryFromCode('nl')
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
        $customer->addBank(
            (new CustomerBank())
                ->setDefault(true)
                ->setAscription('ascriptor')
                ->setAccountNumber('account number')
                ->setBankName('bank name')
                ->setBicCode('bic code')
                ->setCity('city')
                ->setCountryFromCode('nl')
                ->setIban('iban')
                ->setNatBicCode('nat-bic')
                ->setPostcode('postcode')
                ->setState('state')
                ->setField2('address 2')
                ->setField3('address 3')
        );

        $this->document->addCustomer($customer);

        $customer = new Customer();
        $customer->setCode('D654321');
        $customer->setName('Nuck Chorris');
        $customer->setWebsite('http://example.org');
        $customer->setOffice(Office::fromCode("DEV-00001"));
        $customer->setStatusFromString('deleted');

        $this->document->addCustomer($customer);

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
	    <type>DEB</type>
	    <website>http://example.org</website>
	    <financials>
	        <collectionschema>core</collectionschema>
	        <duedays>1</duedays>
	        <ebilling>true</ebilling>
	        <ebillmail>ebillingmail@mail.com</ebillmail>
	        <payavailable>true</payavailable>
	        <paycode>pay-code</paycode>
	        <substitutewith/>
	        <vatcode>vat-code</vatcode>
	        <collectmandate>
		    <firstrundate/>
	            <id>20190510929</id>
		    <signaturedate/>
	        </collectmandate>
            </financials>
        <creditmanagement>
            <basecreditlimit>50</basecreditlimit>
	    <blocked>false</blocked>
	    <comment>comment    comment</comment>
	    <freetext1>true</freetext1>
            <freetext2>free2</freetext2>
	    <freetext3/>
	    <responsibleuser>responsible-user</responsibleuser>
	    <reminderemail>reminderemail@mail.com</reminderemail>
            <sendreminder>true</sendreminder>
        </creditmanagement>
	<invoicing>
	    <discountarticle/>
	</invoicing>
        <addresses>
            <address default="true" id="20190510718" type="invoice">
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
            <bank default="true" id="20190510621">
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
	    <type>DEB</type>
	    <website>http://example.org</website>
	    <financials>
		<collectionschema>core</collectionschema>
		<duedays>30</duedays>
		<ebilling>false</ebilling>
		<ebillmail/>
		<meansofpayment/>
		<payavailable>false</payavailable>
		<paycode/>
		<substitutewith/>
		<vatcode/>
		<collectmandate>
		    <firstrundate/>
		    <id>20190510798</id>
		    <signaturedate/>
		</collectmandate>
	    </financials>
	    <creditmanagement>
		<basecreditlimit>0</basecreditlimit>
		<blocked>false</blocked>
		<comment/>
		<freetext1>false</freetext1>
		<freetext2/>
		<freetext3/>
		<reminderemail/>
		<responsibleuser/>
		<sendreminder>true</sendreminder>
	    </creditmanagement>
	    <invoicing>
		<discountarticle/>
	    </invoicing>
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
    </dimension>
</dimensions>
XML
            ,
            $this->document->saveXML()
        );
    }
}
