<?php

namespace PhpTwinfield\Request\Catalog;

class BrowseField extends Catalog
{
    /**
     * Creates the <list> element and adds it to the property
     * listElement and set the type to 'browsefields'.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->add('type', 'browsefields');
    }
}
