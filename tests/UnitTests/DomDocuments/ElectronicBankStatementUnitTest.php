<?php

namespace Tests\DomDocuments;

use Money\Money;
use PhpTwinfield\DomDocuments\ElectronicBankStatementDocument;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\ElectronicBankStatementTransaction;

class ElectronicBankStatementUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testDocumentationExampleCreatedSuccessfully()
    {
        $statement = new ElectronicBankStatement();
        $statement->setIban("NL91ABNA0417164300");
        $statement->setStartvalue(Money::EUR(768));
        $statement->setDate(new \DateTime("2013-11-08"));
        $statement->setStatementnumber(2);

        $transaction1 = new ElectronicBankStatementTransaction();
        $transaction1->setType("N100");
        $transaction1->setValue(Money::EUR(15100));
        $transaction1->setDescription("Invoice 3722838");

        $transaction2 = new ElectronicBankStatementTransaction();
        $transaction2->setType("N999");
        $transaction2->setValue(Money::EUR(-750));
        $transaction2->setDescription("Costs *300");

        $statement->setTransactions([$transaction1, $transaction2]);

        $domdocument = new ElectronicBankStatementDocument();
        $domdocument->addStatement($statement);

        $this->assertXmlStringEqualsXmlString(<<<XML
<statement target="electronicstatements">
	<iban>NL91ABNA0417164300</iban>
	<date>20131108</date>
	<currency>EUR</currency>
	<statementnumber>2</statementnumber>
	<startvalue>7.68</startvalue>
	<closevalue>151.18</closevalue>
	<transactions>
		<transaction>
			<type>N100</type>
			<reference></reference>
			<debitcredit>credit</debitcredit>
			<value>151.00</value>
			<description>Invoice 3722838</description>
		</transaction>
		<transaction>
			<type>N999</type>
			<reference></reference>
			<debitcredit>debit</debitcredit>
			<value>7.50</value>
			<description>Costs *300</description>
		</transaction>
	</transactions>
</statement>
XML
, $domdocument->saveXML());
    }

    public function testImportDuplicateIsSet()
    {
        $statement = new ElectronicBankStatement();
        $statement->setImportDuplicate(true);
        $statement->setDate(new \DateTimeImmutable("2017-11-30"));
        $statement->setStartvalue(Money::EUR(0));
        $statement->setStatementnumber(236);

        $domdocument = new ElectronicBankStatementDocument();
        $domdocument->addStatement($statement);

        $this->assertXmlStringEqualsXmlString(<<<XML
<?xml version="1.0"?>
<statement target="electronicstatements" importduplicate="1">
	<date>20171130</date>
	<currency>EUR</currency>
	<statementnumber>236</statementnumber>
	<startvalue>0.00</startvalue>
	<closevalue>0.00</closevalue>
	<transactions />
</statement>
XML
            ,$domdocument->saveXML());
    }
}