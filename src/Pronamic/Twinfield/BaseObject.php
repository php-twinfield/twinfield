<?php

namespace Pronamic\Twinfield;

use Pronamic\Twinfield\Message\Message;

/**
 * Twinfield Base object.
 *
 * @author Jop peters <jop@mastercoding.nl>
 */
abstract class BaseObject
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

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    public function hasMessages()
    {
        return count($this->messages) > 0;
    }
}
