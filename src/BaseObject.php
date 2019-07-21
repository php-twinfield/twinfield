<?php

namespace PhpTwinfield;

use PhpTwinfield\Message\Message;

/**
 * Twinfield Base object.
 *
 * @author Jop peters <jop@mastercoding.nl>
 */
abstract class BaseObject implements HasMessageInterface
{
    private $result;
    private $messages;

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function addMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    public function hasMessages()
    {
        if (empty($this->messages)) {
            return false;
        }

        return count($this->messages) > 0;
    }
}
