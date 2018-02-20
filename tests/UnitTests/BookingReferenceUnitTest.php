<?php

namespace PhpTwinfield\UnitTests;

use PhpTwinfield\BookingReference;
use PhpTwinfield\BookingReferenceInterface;
use PhpTwinfield\MatchReference;
use PhpTwinfield\Office;
use PHPUnit\Framework\TestCase;

class BookingReferenceUnitTest extends TestCase
{
    public function testBookingReferenceImplementsInterface()
    {
        self::assertInstanceOf(
            BookingReferenceInterface::class,
            new BookingReference(Office::fromCode('XXX'), 'code', 1234)
        );
    }

    public function testConstructionWorks()
    {
        $matchReference = new BookingReference(Office::fromCode('XXX'), 'code', 1234);

        self::assertSame('XXX', $matchReference->getOffice()->getCode());
        self::assertSame('code', $matchReference->getCode());
        self::assertSame(1234, $matchReference->getNumber());
    }

    public function testFromMatchingReferenceWorks()
    {
        $matchReference = BookingReference::fromMatchReference(
            new MatchReference(Office::fromCode('YYY-10'), 'edoc', 4321000, 25)
        );

        self::assertSame('YYY-10', $matchReference->getOffice()->getCode());
        self::assertSame('edoc', $matchReference->getCode());
        self::assertSame(4321000, $matchReference->getNumber());
    }
}
