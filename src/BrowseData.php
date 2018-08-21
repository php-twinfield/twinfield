<?php

namespace PhpTwinfield;

class BrowseData
{
    /** @var int */
    private $first;

    /** @var int */
    private $last;

    /** @var int */
    private $total;

    /** @var BrowseDataHeader[] */
    private $headers;

    /** @var BrowseDataRow[] */
    private $rows;

    /**
     * BrowseData constructor.
     */
    public function __construct()
    {
        $this->headers = [];
        $this->rows = [];
    }

    /**
     * @return int
     */
    public function getFirst(): int
    {
        return $this->first;
    }

    /**
     * @param int $first
     * @return BrowseData
     */
    public function setFirst(int $first): BrowseData
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @return int
     */
    public function getLast(): int
    {
        return $this->last;
    }

    /**
     * @param int $last
     * @return BrowseData
     */
    public function setLast(int $last): BrowseData
    {
        $this->last = $last;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return BrowseData
     */
    public function setTotal(int $total): BrowseData
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return BrowseDataHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param BrowseDataHeader $browseDataHeader
     */
    public function addHeader(BrowseDataHeader $browseDataHeader)
    {
        $this->headers[] = $browseDataHeader;
    }

    /**
     * @return BrowseDataRow[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param BrowseDataRow $browseDataRow
     */
    public function addRow(BrowseDataRow $browseDataRow)
    {
        $this->rows[] = $browseDataRow;
    }
}
