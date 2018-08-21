<?php

namespace PhpTwinfield;

class BrowseField
{
    /** @var string */
    private $code;

    /** @var string */
    private $dataType;

    /** @var string */
    private $finder;

    /** @var BrowseFieldOption[] */
    private $options;

    /** @var bool */
    private $canOrder;

    /**
     * BrowseField constructor.
     */
    public function __construct()
    {
        $this->options = [];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return BrowseField
     */
    public function setCode(string $code): BrowseField
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     * @return BrowseField
     */
    public function setDataType(string $dataType): BrowseField
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinder(): string
    {
        return $this->finder;
    }

    /**
     * @param string $finder
     * @return BrowseField
     */
    public function setFinder(string $finder): BrowseField
    {
        $this->finder = $finder;
        return $this;
    }

    /**
     * @return BrowseFieldOption[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param BrowseFieldOption $option
     * @return BrowseField
     */
    public function addOption(BrowseFieldOption $option): BrowseField
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanOrder(): bool
    {
        return $this->canOrder;
    }

    /**
     * @param bool $canOrder
     * @return BrowseField
     */
    public function setCanOrder(bool $canOrder): BrowseField
    {
        $this->canOrder = $canOrder;
        return $this;
    }
}
