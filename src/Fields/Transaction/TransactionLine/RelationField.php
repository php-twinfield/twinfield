<?php

namespace PhpTwinfield\Fields\Transaction\TransactionLine;

trait RelationField
{
    /**
     * Relation field
     * Used by: BaseTransactionLine
     *
     * @var int|null
     */
    private $relation;

    /**
     * @return null|int
     */
    public function getRelation(): ?int
    {
        return $this->relation;
    }

    /**
     * @param null|int $relation
     * @return $this
     */
    public function setRelation(?int $relation): self
    {
        $this->relation = $relation;
        return $this;
    }
}