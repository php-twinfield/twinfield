<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\BookingReference;
use PhpTwinfield\BookingReferenceInterface;
use PhpTwinfield\MatchReference;
use PhpTwinfield\MatchReferenceInterface;
use PhpTwinfield\Office;
use PHPUnit\Framework\TestCase;

class MatchReferenceUnitTest extends TestCase
{
    public function testMatchReferenceImplementsInterface()
    {
        self::assertInstanceOf(
            MatchReferenceInterface::class,
            new MatchReference(Office::fromCode('XXX'), 'code', 1234, 1)
        );
    }

    public function testMatchReferenceDoesNotImplementBookingInterface()
    {
        self::assertNotInstanceOf(
            BookingReferenceInterface::class,
            new MatchReference(Office::fromCode('XXX'), 'code', 1234, 1)
        );
    }

    public function testConstructionWorks()
    {
        $matchReference = new MatchReference(Office::fromCode('XXX'), 'code', 1234, 1);

        self::assertSame('XXX', $matchReference->getOffice()->getCode());
        self::assertSame('code', $matchReference->getCode());
        self::assertSame(1234, $matchReference->getNumber());
        self::assertSame(1, $matchReference->getLineId());
    }

    public function testFromBookingReferenceWorks()
    {
        $matchReference = MatchReference::fromBookingReference(
            new BookingReference(Office::fromCode('YYY-10'), 'edoc', 4321000),
            20
        );

        self::assertSame('YYY-10', $matchReference->getOffice()->getCode());
        self::assertSame('edoc', $matchReference->getCode());
        self::assertSame(4321000, $matchReference->getNumber());
        self::assertSame(20, $matchReference->getLineId());
    }
}
