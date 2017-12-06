<?php
namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\DomDocuments\BankTransactionDocument;
use PhpTwinfield\Enums\DebitCredit;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;

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

        $line2 = new Detail();
        $line2->setValue(Money::EUR(100));
        $line2->setId(38862);

        $line3 = new Total();
        $line3->setValue(Money::EUR(-100));
        $line3->setId(38863);

        $transaction->setTransactions([$line1, $line2, $line3]);

        $this->document->addBankTransaction($transaction);

        $this->assertXmlStringEqualsXmlString(<<<XML
<?xml version="1.0"?>
<transactions>
	<transaction autobalancevat="true" destiny="temporary">
		<header>
			<office>dev-10000</office>
			<currency>eur</currency>
			<startvalue>0.00</startvalue>
			<closevalue>1.21</closevalue>
		</header>
		<transactions>
			<transaction id="38861" type="total">
				<debitcredit>credit</debitcredit>
				<value>1.21</value>
			</transaction>
			<transaction id="38862" type="detail">
				<debitcredit>credit</debitcredit>
				<value>1.00</value>
			</transaction>
			<transaction id="38863" type="total">
				<debitcredit>debit</debitcredit>
				<value>1.00</value>
			</transaction>  
		</transactions>
	</transaction>
</transactions>

XML
    ,$this->document->saveXML());
    }
}