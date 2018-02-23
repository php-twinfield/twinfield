<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\BookingReference;

class BookingReferenceDeletionDocument extends BaseDocument
{
    protected function getRootTagName(): string
    {
        return "transaction";
    }

    public function __construct(BookingReference $bookingReference, string $reason)
    {
        parent::__construct("1.0", "UTF-8");

        $this->rootElement->setAttribute("action", "delete");
        $this->rootElement->setAttribute("reason", $reason);

        $this->appendOfficeField($this->rootElement, $bookingReference->getOffice());

        $this->rootElement->appendChild(
            $this->createNodeWithTextContent("code", $bookingReference->getCode())
        );

        $this->rootElement->appendChild(
            $this->createNodeWithTextContent("number", $bookingReference->getNumber())
        );
    }
}