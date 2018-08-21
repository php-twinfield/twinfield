<?php

namespace PhpTwinfield\Request\Read;

use PhpTwinfield\Office;

class BrowseDefinition extends Read
{
    /**
     * Sets office and code if they are present.
     *
     * @param string $code
     * @param Office|null $office
     */
    public function __construct(string $code, Office $office = null)
    {
        parent::__construct();

        $this->add('type', 'browse');

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Sets the office code for this BrowseData request.
     *
     * @access public
     * @param Office $office
     * @return BrowseDefinition
     */
    public function setOffice(Office $office)
    {
        $this->add('office', $office->getCode());
        return $this;
    }

    /**
     * Sets the code for this BrowseData request.
     *
     * @access public
     * @param string $code
     * @return BrowseDefinition
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}
