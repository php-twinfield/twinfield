<?php

namespace PhpTwinfield\Fields\Dimensions\Level2\Customer;

trait CommentField
{
    /**
     * Comment field
     * Used by: CustomerCreditManagement
     *
     * @var string|null
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return $this
     */
    public function setComment(?string $comment)
    {
        $this->comment = $comment;
        return $this;
    }
}