<?php

namespace PhpTwinfield\Transactions\TransactionLineFields;

trait CommentField
{
    /**
     * @var string|null
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return $this
     */
    public function setComment($comment = null)
    {
        $this->comment = $comment;
        return $this;
    }
}