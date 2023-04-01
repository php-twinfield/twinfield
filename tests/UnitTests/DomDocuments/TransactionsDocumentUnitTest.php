<?php

declare(strict_types=1);

namespace PhpTwinfield\UnitTests;

use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpTwinfield\BaseTransaction;
use PhpTwinfield\CashTransaction;
use PhpTwinfield\CashTransactionLine;
use PhpTwinfield\DomDocuments\TransactionsDocument;
use PhpTwinfield\Enums\Destiny;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Office;
use PhpTwinfield\PurchaseTransaction;
use PhpTwinfield\PurchaseTransactionLine;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\SalesTransactionLine;
use PHPUnit\Framework\TestCase;
use TypeError;

final class TransactionsDocumentUnitTest extends TestCase
{
    /**
     * @test
     * @dataProvider transactionsProvider
     */
    public function vat_transaction_line_dim1_can_be_empty(BaseTransaction $transaction): void
    {
        $caughtException = null;
        $document = new TransactionsDocument();

        try {
            $document->addTransaction($transaction);
        } catch (TypeError $e) {
            $caughtException = $e;
        }

        $this->assertNull($caughtException);
    }

    public function transactionsProvider(): array
    {
        return [
            'cash transaction' => [$this->fakeCashTransactionWithoutDim1OnVatLine()],
            'journal transaction' => [$this->fakeJournalTransactionWithoutDim1OnVatLine()],
            'purchase transaction' => [$this->fakeSalesTransactionWithoutDim1OnVatLine()],
            'sales transaction' => [$this->fakeSalesTransactionWithoutDim1OnVatLine()],
        ];
    }

    private function fakeCashTransactionWithoutDim1OnVatLine(): CashTransaction
    {
        $cashTransaction = new CashTransaction();
        $cashTransaction
            ->setOffice(Office::fromCode('001'))
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('CASH')
            ->setCurrency(new Currency('EUR'))
            ->setDate(new DateTimeImmutable('2013-11-04'))
            ->setStatementnumber(4)
            ->setStartvalue(Money::EUR(97401));

        $totalLine = new CashTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId(1)
            ->setDim1('')
            ->setValue(Money::EUR(12100));

        $detailLine = new CashTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId(2)
            ->setDim1('')
            ->setDim2('1000')
            ->setValue(Money::EUR(10000))
            ->setVatCode('VH')
            ->setInvoiceNumber('11001770')
            ->setDescription('Invoice paid');

        $vatLine = new CashTransactionLine();
        $vatLine
            ->setLineType(LineType::VAT())
            ->setId(3)
            ->setValue(Money::EUR(2100))
            ->setVatCode('VH');

        $cashTransaction
            ->addLine($totalLine)
            ->addLine($detailLine)
            ->addLine($vatLine);

        return $cashTransaction;
    }

    private function fakeJournalTransactionWithoutDim1OnVatLine(): JournalTransaction
    {
        $journalTransaction = new JournalTransaction();
        $journalTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setCode('MEMO')
            ->setCurrency(new Currency('EUR'))
            ->setDate(new \DateTimeImmutable('2013-11-04'))
            ->setOffice(Office::fromCode('001'));

        $detailLine = new JournalTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId(2)
            ->setDim1('')
            ->setDim2('1000')
            ->setValue(Money::EUR(10000))
            ->setVatCode('VH')
            ->setInvoiceNumber('11001770')
            ->setDescription('Invoice paid');

        $vatLine = new JournalTransactionLine();
        $vatLine
            ->setLineType(LineType::VAT())
            ->setId(3)
            ->setValue(Money::EUR(2100))
            ->setVatCode('VH');

        $journalTransaction
            ->addLine($detailLine)
            ->addLine($vatLine);

        return $journalTransaction;
    }

    private function fakePurchaseTransactionWithoutDim1OnVatLine(): PurchaseTransaction
    {
        $purchaseTransaction = new PurchaseTransaction();
        $purchaseTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('INK')
            ->setCurrency(new Currency('EUR'))
            ->setDate(new DateTimeImmutable('2013-05-02'))
            ->setPeriod('2013/05')
            ->setInvoiceNumber('20130-5481')
            ->setPaymentReference('+++100/0160/01495+++')
            ->setOffice(Office::fromCode('001'))
            ->setDueDate(new DateTimeImmutable('2013-05-06'));

        $totalLine = new PurchaseTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId(1)
            ->setDim1('')
            ->setDim2('2000')
            ->setValue(Money::EUR(12100))
            ->setDescription('');

        $detailLine = new PurchaseTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId(2)
            ->setDim1('')
            ->setValue(Money::EUR(10000))
            ->setDescription('Outfit')
            ->setVatCode('IH');

        $vatLine = new PurchaseTransactionLine();
        $vatLine
            ->setLineType(LineType::VAT())
            ->setId(3)
            ->setValue(Money::EUR(2100))
            ->setVatCode('VH');

        $purchaseTransaction
            ->addLine($totalLine)
            ->addLine($detailLine)
            ->addLine($vatLine);

        return $purchaseTransaction;
    }

    private function fakeSalesTransactionWithoutDim1OnVatLine(): SalesTransaction
    {
        $salesTransaction = new SalesTransaction();
        $salesTransaction
            ->setDestiny(Destiny::TEMPORARY())
            ->setRaiseWarning(false)
            ->setCode('SLS')
            ->setCurrency(new Currency('EUR'))
            ->setDate(new DateTimeImmutable('2013-05-02'))
            ->setPeriod('2013/05')
            ->setInvoiceNumber('20130-6000')
            ->setPaymentReference('+++100/0160/01495+++')
            ->setOffice(Office::fromCode('001'));

        $totalLine = new SalesTransactionLine();
        $totalLine
            ->setLineType(LineType::TOTAL())
            ->setId(1)
            ->setDim1('')
            ->setDim2('1000')
            ->setValue(Money::EUR(12100))
            ->setDescription('');

        $detailLine = new SalesTransactionLine();
        $detailLine
            ->setLineType(LineType::DETAIL())
            ->setId(2)
            ->setDim1('')
            ->setValue(Money::EUR(10000))
            ->setDescription('Outfit')
            ->setVatCode('VH');

        $vatLine = new SalesTransactionLine();
        $vatLine
            ->setLineType(LineType::VAT())
            ->setId(3)
            ->setValue(Money::EUR(2100))
            ->setVatCode('VH');

        $salesTransaction
            ->addLine($totalLine)
            ->addLine($detailLine)
            ->addLine($vatLine);

        return $salesTransaction;
    }
}
