<?php
namespace PhpTwinfield\UnitTests;

use Money\Money;
use PhpTwinfield\Enums\LineType;
use PhpTwinfield\SalesTransaction;
use PhpTwinfield\SalesTransactionLine;

class SalesTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testGetLinesReturnsLinesInSameOrderWhenAlreadyInsertedInTheRightOrder()
    {
        $sales_transaction = new SalesTransaction();

        $this->addLineWithDescription($sales_transaction, LineType::TOTAL(), '0');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '1');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '2');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '3');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '4');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '5');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '6');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '7');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '8');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '9');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '10');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '11');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '12');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '13');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '14');

        self::assertLinesInOrderBasedOnDescription($sales_transaction, 15);
    }

    public function testGetLinesReturnsLinesInSameOrderWhenInsertionOrderiExtremelyMixed()
    {
        $sales_transaction = new SalesTransaction();

        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '9');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '1');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '10');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '2');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '11');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '3');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '12');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '4');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '13');
        $this->addLineWithDescription($sales_transaction, LineType::TOTAL(), '0');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '5');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '6');
        $this->addLineWithDescription($sales_transaction, LineType::VAT(), '14');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '7');
        $this->addLineWithDescription($sales_transaction, LineType::DETAIL(), '8');

        self::assertLinesInOrderBasedOnDescription($sales_transaction, 15);
    }

    private function addLineWithDescription(
        SalesTransaction $salesTransaction,
        LineType $line_type,
        string $description
    ): SalesTransactionLine {
        $line = new SalesTransactionLine();
        $line->setLineType($line_type);
        $line->setDescription($description);
        $line->setValue(Money::EUR(0));

        $salesTransaction->addLine($line);

        return $line;
    }

    private static function assertLinesInOrderBasedOnDescription(
        SalesTransaction $sales_transaction,
        int $expected_line_count
    ) {
        $i = 0;
        /** @var SalesTransactionLine $line */
        foreach ($sales_transaction->getLines() as $line) {
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
}