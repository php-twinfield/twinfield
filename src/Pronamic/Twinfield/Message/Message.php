<?php

namespace Pronamic\Twinfield\Message;

class Message
{
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';

    /**
     * The message type, see type constants.
     *
     * @var string
     */
    private $type;

    /**
     * The actual textual message.
     *
     * @var string
     */
    private $message;

    /**
     * The field the message belongs to.
     *
     * @var string
     */
    private $field;

    /**
     * Gets the The message type, see type constants.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the The message type, see type constants.
     *
     * @param string $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the The actual textual message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the The actual textual message.
     *
     * @param string $message the message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the The field the message belongs to.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets the The field the message belongs to.
     *
     * @param string $field the field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }
}
