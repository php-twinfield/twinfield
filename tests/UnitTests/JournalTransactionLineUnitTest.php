<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Office;

class JournalTransactionLineUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetReferenceFromLine()
    {
        $purchase = new JournalTransaction();
        $purchase->setOffice(Office::fromCode("XXX99999"));
        $purchase->setNumber("201300003");
        $purchase->setCode("MEMO");

        $line = new JournalTransactionLine();
        $line->setId(1);

        $purchase->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300003", $reference->getNumber());
        $this->assertEquals("1", $reference->getLineId());
        $this->assertEquals("MEMO", $reference->getCode());
    }
}
