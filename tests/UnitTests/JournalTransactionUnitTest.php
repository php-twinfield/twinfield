<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\JournalTransaction;
use PhpTwinfield\JournalTransactionLine;
use PhpTwinfield\Office;

class JournalTransactionUnitTest extends \PHPUnit\Framework\TestCase
{
    public function testCanGetReferenceFromLine()
    {
        $journal = new JournalTransaction();
        $journal->setOffice(Office::fromCode("XXX99999"));
        $journal->setNumber("201300003");
        $journal->setCode("MEMO");

        $line = new JournalTransactionLine();
        $line->setId(1);

        $journal->addLine($line);

        $reference = $line->getReference();

        $this->assertEquals(Office::fromCode("XXX99999"), $reference->getOffice());
        $this->assertEquals("201300003", $reference->getNumber());
        $this->assertEquals("1", $reference->getLineId());
        $this->assertEquals("MEMO", $reference->getCode());
    }
}
