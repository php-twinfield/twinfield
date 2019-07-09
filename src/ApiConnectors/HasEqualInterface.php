<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\HasMessageInterface;
use PhpTwinfield\Response\MappedResponseCollection;

/**
 * Provides an interface for ApiConnectors with test equal functionality
 *
 */
interface HasEqualInterface
{
    public function testEqual(HasMessageInterface $returnedObject, HasMessageInterface $sentObject): array;

    public function sendAll(array $objects, bool $reSend): MappedResponseCollection;
}
