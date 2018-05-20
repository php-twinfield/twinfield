<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;
use PhpTwinfield\Transactions\BankTransactionLine\Vat;

class BankTransactionDocumentUnitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var BankTransactionDocument
     */
    protected $document;

    protected function setUp()
    {
        parent::setUp();

        $this->document = new BankTransactionDocument();
    }

    public function testXmlIsCreatedPerSpec()
    {
        $transaction = new BankTransaction();
        $transaction->setDestiny(Destiny::TEMPORARY());
        $transaction->setAutoBalanceVat(true);
        $transaction->setOffice(Office::fromCode("DEV-10000"));
        $transaction->setStartvalue(Money::EUR(0));

        $line1 = new Total();
        $line1->setValue(Money::EUR(121));
        $line1->setId(38861);
        $line1->setVatTotal(Money::EUR(21));
        $line1->setVatBaseTotal(Money::EUR(21));
        $line1->setVatRepTotal(Money::EUR(21));
        $line1->setComment("Round House Kicks & Beard Fists");

        $line2 = new Detail();
        $line2->setValue(Money::EUR(100));
        $line2->setId(38862);
        $line2->setVatValue(Money::EUR(100)); // Not sure?
        $line2->setVatBaseValue(Money::EUR(100));
        $line2->setVatRepValue(Money::EUR(100));

        $line3 = new Detail();
        $line3->setValue(Money::EUR(-100));
        $line3->setId(38863);
        $line3->setDestOffice(Office::fromCode("DEV-11000"));

        $line4 = new Vat();
        $line4->setValue(Money::EUR(21));
        $line4->setId(38864);

        $transaction->setLines([$line1, $line2, $line3, $line4]);

        $line3->setComment(
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse facilisis lobortis arcu in tincidunt. Mauris urna enim, commodo nec feugiat quis, pharetra vel sem. Etiam ullamcorper eleifend tellus non viverra. Nulla facilisi. Donec sed orci aliquam."
        );

        $this->document->addBankTransaction($transaction);

        $this->assertXmlStringEqualsXmlString(
            <<<XML
<?xml version="1.0"?>
<transactions>
	<transaction autobalancevat="true" destiny="temporary">
		<header>
			<office>dev-10000</office>
			<currency>eur</currency>
			<startvalue>0.00</startvalue>
			<closevalue>0.21</closevalue>
		</header>
		<lines>
			<line id="38861" type="total">
				<debitcredit>debit</debitcredit>
				<value>1.21</value>
				<vattotal>0.21</vattotal>
				<vatbasetotal>0.21</vatbasetotal>
				<vatreptotal>0.21</vatreptotal>
				<comment>Round House Kicks &amp; Beard Fists</comment>
			</line>
			<line id="38862" type="detail">
				<debitcredit>credit</debitcredit>
				<value>1.00</value>
				<vatvalue>1.00</vatvalue>
				<vatbasevalue>1.00</vatbasevalue>
				<vatrepvalue>1.00</vatrepvalue>
			</line>
			<line id="38863" type="detail">
				<debitcredit>debit</debitcredit>
				<value>1.00</value>
				<destoffice>DEV-11000</destoffice>
				<comment>lorem ipsum dolor sit amet, consectetur adipiscing elit. suspendisse facilisis lobortis arcu in tincidunt. mauris urna enim, commodo nec feugiat quis, pharetra vel sem. etiam ullamcorper eleifend tellus non viverra. nulla facilisi. donec sed orci aliquam.</comment>
			</line>
			<line id="38864" type="vat">
				<debitcredit>credit</debitcredit>
				<value>0.21</value>
			</line>
		</lines>
	</transaction>
</transactions>
XML
            ,
            $this->document->saveXML()
        );
    }
}