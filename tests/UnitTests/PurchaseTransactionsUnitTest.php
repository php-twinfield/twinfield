<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Enums\LineType;
use PhpTwinfield\Office;
use PhpTwinfield\PurchaseTransaction;
use PhpTwinfield\PurchaseTransactionLine;
use PHPUnit\Framework\TestCase;

class PurchaseTransactionsUnitTest extends TestCase
{
    /**
     * @link https://github.com/php-twinfield/twinfield/issues/15
     */
    public function testTotalLinesAreReturnedFirst()
    {
        $purchase = new PurchaseTransaction();

        $detail = new PurchaseTransactionLine();
        $detail->setLineType(LineType::DETAIL());
        $detail->setId(3);

        $vat = new PurchaseTransactionLine();
        $vat->setLineType(LineType::VAT());
        $vat->setId(4);

        $total = new PurchaseTransactionLine();
        $total->setLineType(LineType::TOTAL());
        $total->setId(5);


        $purchase
            ->addLine($detail)
            ->addLine($vat)
            ->addLine($total);

        $lines = $purchase->getLines();

        $this->assertSame($total, reset($lines));
    }

    public function testCanGetReferenceFromLine()
    {
        $purchase = new PurchaseTransaction();
        $purchase->setOffice(Office::fromCode("XXX99999"));
        $purchase->setNumber("201300021");
        $purchase->setCode("INK");

        $line = new PurchaseTransactionLine();
        $line->setLineType(LineType::DETAIL());
        $line->setId(2);

        $purchase->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300021", $reference->getNumber());
        $this->assertEquals("2", $reference->getLineId());
        $this->assertEquals("INK", $reference->getCode());
    }
}
