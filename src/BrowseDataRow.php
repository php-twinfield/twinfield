<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\OfficeField;

class BrowseDataRow
{
    use CodeField;
    use OfficeField;

    /** @var int */
    private $number;

    /** @var int */
    private $line;

    /** @var BrowseDataCell[] */
    private $cells;

    /**
     * BrowseDataRow constructor.
     */
    public function __construct()
    {
        $this->cells = [];
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return BrowseDataRow
     */
    public function setNumber(int $number): BrowseDataRow
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return BrowseDataRow
     */
    public function setLine(int $line): BrowseDataRow
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return BrowseDataCell[]
     */
    public function getCells(): array
    {
        return $this->cells;
    }

    /**
     * @param BrowseDataCell $browseDataCell
     */
    public function addCell(BrowseDataCell $browseDataCell)
    {
        $this->cells[] = $browseDataCell;
    }
}
