<?php

namespace PhpTwinfield\Fields;

trait CommentField
{
    /**
     * Comment field
     * Used by: BaseTransactionLine, CustomerCreditManagement
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