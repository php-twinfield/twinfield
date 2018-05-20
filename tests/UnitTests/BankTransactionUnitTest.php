<?php

namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\BankTransaction;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Office;
use PhpTwinfield\Transactions\BankTransactionLine\Base;
use PhpTwinfield\Transactions\BankTransactionLine\Detail;
use PhpTwinfield\Transactions\BankTransactionLine\Total;

class BankTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetReferenceFromLine()
    {
        $bank = new BankTransaction();
        $bank->setOffice(Office::fromCode("XXX99999"));
        $bank->setNumber("201300003");
        $bank->getNumber();
        $bank->setCode("MEMO");

        $line = new Detail();
        $line->setId(1);
        $line->setValue(Money::EUR(1));

        $bank->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300003", $reference->getNumber());
        $this->assertEquals("1", $reference->getLineId());
        $this->assertEquals("MEMO", $reference->getCode());
    }

    public function testGetLineReturnsLinesInSameOrderButAlwaysWithTotalAtTheTop()
    {
        $total_line_count = 287;

        $bank_transaction = new BankTransaction();

        for ($i = 1; $i < $total_line_count; $i++) {
            $this->addDetailLineWithDescription($bank_transaction, (string)$i);
        }

        $this->addTotalLineWithDescription($bank_transaction, '0');

        self::assertLinesInOrderBasedOnDescription($bank_transaction, $total_line_count);
    }

    private static function assertLinesInOrderBasedOnDescription(
        BankTransaction $bank_transaction,
        int $expected_line_count
    ) {
        $i = 0;
        /** @var Base $line */
        foreach ($bank_transaction->getLines() as $line) {
            self::assertSame(
                $i,
                (int)$line->getDescription(),
                'Expected line #' . $i . ', but got line #' . $line->getDescription() .
                ' of type ' . $line->getLineType()->getValue() . '. Wrong order!'
            );
            $i++;
        }

        self::assertSame($expected_line_count, $i, 'Number of returned lines does not match number of added lines');
    }

    private function addTotalLineWithDescription(BankTransaction $bankTransaction, string $description): Total
    {
        $line = new Total();
        $line->setDescription($description);
        $line->setValue(Money::EUR(0));

        $bankTransaction->addLine($line);

        return $line;
    }

    private function addDetailLineWithDescription(BankTransaction $bankTransaction, string $description): Detail
    {
        $line = new Detail();
        $line->setDescription($description);
        $line->setValue(Money::EUR(0));

        $bankTransaction->addLine($line);

        return $line;
    }

    public function testGetLinesReturnsLinesInTheSameOrderAsTheyWereAdded()
    {
        $total_line_count = 300;

        $bank_transaction = new BankTransaction();

        $this->addTotalLineWithDescription($bank_transaction, '0');

        for ($i = 1; $i < $total_line_count; $i++) {
            $this->addDetailLineWithDescription($bank_transaction, (string)$i);
        }

        self::assertLinesInOrderBasedOnDescription($bank_transaction, $total_line_count);
    }

    public function testGetLinesReturnsEmptyArrayWhenNoLinesSet()
    {
        $bank_transaction = new BankTransaction();

        self::assertCount(0, $bank_transaction->getLines());
    }

    public function testGetLinesReturnsLinesInSameOrderWhenOnlyDetailsAdded()
    {
        $bank_transaction = new BankTransaction();

        $this->addDetailLineWithDescription($bank_transaction, '0');
        $this->addDetailLineWithDescription($bank_transaction, '1');
        $this->addDetailLineWithDescription($bank_transaction, '2');

        self::assertLinesInOrderBasedOnDescription($bank_transaction, 3);
    }

    public function testGetLinesReturnsOnlyTotalLineWhenOnlyTotalLineIsAdded()
    {
        $bank_transaction = new BankTransaction();

        $this->addTotalLineWithDescription($bank_transaction, '0');

        self::assertCount(1, $bank_transaction->getLines());
        self::assertTrue($bank_transaction->getLines()[0]->getLineType()->equals(LineType::TOTAL()));
    }

    public function testAdding1TotalLineAnd499DetailLinesToABankTransactionWorks()
    {
        $total_line_count = 500;

        $bank_transaction = new BankTransaction();

        $this->addTotalLineWithDescription($bank_transaction, '0');
        for ($i = 1; $i < $total_line_count; $i++) {
            $this->addDetailLineWithDescription($bank_transaction, (string)$i);
        }

        self::assertCount($total_line_count, $bank_transaction->getLines());
    }

    public function testAdding1TotalLineWith500DetailLinesToABankTransactionThrows()
    {
        $total_line_count = 501;

        $bank_transaction = new BankTransaction();

        $this->addTotalLineWithDescription($bank_transaction, '0');

        $this->expectException(\InvalidArgumentException::class);

        for ($i = 1; $i < $total_line_count; $i++) {
            $this->addDetailLineWithDescription($bank_transaction, (string)$i);
        }
    }

    public function testAddLineThrowsAndIgnoresWhenAddingASecondTotalLine()
    {
        $bank_transaction = new BankTransaction();

        $this->addTotalLineWithDescription($bank_transaction, '0');

        try {
            $this->addTotalLineWithDescription($bank_transaction, '0');

            self::fail('An exception should have been thrown when adding a second total line');
        } catch (\InvalidArgumentException $e) {
            self::assertCount(1, $bank_transaction->getLines());
        }
    }
}
