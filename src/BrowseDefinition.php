<?php

namespace PhpTwinfield;

use PhpTwinfield\Fields\CodeField;
use PhpTwinfield\Fields\NameField;
use PhpTwinfield\Fields\OfficeField;
use PhpTwinfield\Fields\ShortNameField;

class BrowseDefinition extends BaseObject
{
    use CodeField;
    use NameField;
    use OfficeField;
    use ShortNameField;

    /** @var bool */
    private $visible;

    /** @var BrowseColumn[] */
    private $columns;

    /**
     * BrowseDefinition constructor.
     */
    public function __construct()
    {
        $this->columns = [];
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
     * @return BrowseDefinition
     */
    public function setVisible(bool $visible): BrowseDefinition
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return BrowseColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param BrowseColumn $column
     * @return $this
     */
    public function addColumn(BrowseColumn $column)
    {
        $this->columns[$column->getID()] = $column;
        return $this;
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeColumn($id)
    {
        if (array_key_exists($id, $this->columns)) {
            unset($this->columns[$id]);
            return true;
        } else {
            return false;
        }
    }

}
