<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\Enums\LineType;
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
        $detail->setType(LineType::DETAIL());
        $detail->setId(3);

        $vat = new PurchaseTransactionLine();
        $vat->setType(LineType::VAT());
        $vat->setId(4);

        $total = new PurchaseTransactionLine();
        $total->setType(LineType::TOTAL());
        $total->setId(5);


        $purchase
            ->addLine($detail)
            ->addLine($vat)
            ->addLine($total);

        $lines = $purchase->getLines();

        $this->assertSame($total, reset($lines));
    }
}
