<?php

namespace PhpTwinfield;

use PhpTwinfield\Message\Message;

/**
 * Provides an interface for BaseObject
 *
 * @see BaseObject
 * @see Message
 *
 */
interface HasMessageInterface
{
    /**
     * Adds an error message to an object
     */
    public function addMessage(Message $message): void;
}