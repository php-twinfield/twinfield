<?php

namespace PhpTwinfield;

use PhpTwinfield\Enums\BrowseColumnOperator;

class BrowseColumn
{
    /** @var int */
    private $id;

    /** @var string */
    private $field;

    /** @var string|null */
    private $label;

    /** @var bool */
    private $visible;

    /** @var bool */
    private $ask;

    /** @var BrowseColumnOperator */
    private $operator;

    /** @var string|null */
    private $from;

    /** @var string|null */
    private $to;

    /**
     * BrowseColumn constructor.
     */
    public function __construct()
    {
        $this->visible = false;
        $this->ask = false;
        $this->operator = BrowseColumnOperator::NONE();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return BrowseColumn
     */
    public function setId(int $id): BrowseColumn
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     * @return BrowseColumn
     */
    public function setField(string $field): BrowseColumn
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return BrowseColumn
     */
    public function setLabel(string $label): BrowseColumn
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return BrowseColumn
     */
    public function setVisible(bool $visible): BrowseColumn
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAsk(): bool
    {
        return $this->ask;
    }

    /**
     * @param bool $ask
     * @return BrowseColumn
     */
    public function setAsk(bool $ask): BrowseColumn
    {
        $this->ask = $ask;
        return $this;
    }

    /**
     * @return BrowseColumnOperator
     */
    public function getOperator(): BrowseColumnOperator
    {
        return $this->operator;
    }

    /**
     * @param BrowseColumnOperator $operator
     * @return BrowseColumn
     */
    public function setOperator(BrowseColumnOperator $operator): BrowseColumn
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return BrowseColumn
     */
    public function setFrom(string $from): BrowseColumn
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return BrowseColumn
     */
    public function setTo(string $to): BrowseColumn
    {
        $this->to = $to;
        return $this;
    }
}
