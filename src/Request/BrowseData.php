<?php

namespace PhpTwinfield\Request;

use DOMDocument;
use DOMElement;
use PhpTwinfield\Util;
use PhpTwinfield\BrowseColumn;
use PhpTwinfield\BrowseSortField;
use Webmozart\Assert\Assert;

class BrowseData extends DOMDocument
{
    /**
     * Holds the <columns> element that all
     * column elements should be a child of.
     *
     * @var DOMElement
     */
    private $columnsElement;

    /**
     * Holds the <sort> element that all sort fields elements should be a child of.
     *
     * @var DOMElement
     */
    private $sortElement;

    /**
     * Creates the <columns> element with the browse code as attribute, and creates elements for the given columns and
     * sort fields.
     *
     * @param string $code
     * @param BrowseColumn[] $columns
     * @param BrowseSortField[] $sortFields
     */
    public function __construct(string $code, array $columns, array $sortFields = [])
    {
        parent::__construct();

        Assert::allIsInstanceOf($columns, BrowseColumn::class);
        Assert::allIsInstanceOf($sortFields, BrowseSortField::class);

        $this->columnsElement = $this->createElement('columns');
        $this->columnsElement->setAttribute('code', $code);

        $this->sortElement = $this->createElement('sort');
        $this->columnsElement->appendChild($this->sortElement);

        $this->addColumns($columns);
        $this->addSortFields($sortFields);

        $this->appendChild($this->columnsElement);
    }

    /**
     * Add the sort field elements to the <sort> element of this element.
     *
     * @param BrowseSortField[] $sortFields
     */
    public function addSortFields(array $sortFields)
    {
        foreach ($sortFields as $sortField) {
            $this->addSortField($sortField);
        }
    }

    /**
     * Add the sort field to the <sort> element of this element.
     *
     * @param BrowseSortField $sortField
     */
    public function addSortField(BrowseSortField $sortField)
    {
        $sortFieldElement = $this->createElement('field', $sortField->getCode());

        if ($sortField->getOrder() !== null) {
            $sortFieldElement->setAttribute('order', $sortField->getOrder()->getValue());
        }

        $this->sortElement->appendChild($sortFieldElement);
    }

    /**
     * Add the columns to this element.
     *
     * @param BrowseColumn[] $columns
     */
    public function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    /**
     * Add the column to this element.
     *
     * @param BrowseColumn $column
     */
    public function addColumn(BrowseColumn $column)
    {
        $columnElement = $this->createElement('column');

        $columnElement->appendChild($this->createElement('field', $column->getField()));
        $columnElement->appendChild($this->createElement('label', $column->getLabel()));
        $columnElement->appendChild($this->createElement('visible', Util::formatBoolean($column->isVisible())));
        $columnElement->appendChild($this->createElement('ask', Util::formatBoolean($column->isAsk())));
        $columnElement->appendChild($this->createElement('operator', $column->getOperator()->getValue()));
        $columnElement->appendChild($this->createElement('from', $column->getFrom()));
        $columnElement->appendChild($this->createElement('to', $column->getTo()));

        $this->columnsElement->appendChild($columnElement);
    }
}
