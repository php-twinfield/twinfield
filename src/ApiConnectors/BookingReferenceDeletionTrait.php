<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\BookingReference;
use PhpTwinfield\DomDocuments\BookingReferenceDeletionDocument;
use PhpTwinfield\Exception;
use PhpTwinfield\Services\ProcessXmlService;

trait BookingReferenceDeletionTrait
{
    abstract protected function getProcessXmlService(): ProcessXmlService;

    /**
     * Delete a bank / sales transaction by its booking reference.
     *
     * @param BookingReference $bookingReference
     * @param string $reason A textual reason that can be shown to humans.
     * @throws Exception
     */
    public function delete(BookingReference $bookingReference, string $reason): void
    {
        $document = new BookingReferenceDeletionDocument($bookingReference, $reason);

        $response = $this->getProcessXmlService()->sendDocument($document);
        $response->assertSuccessful();
    }
}