<?php
namespace PhpTwinfield\Request;

use DOMDocument;
use DOMElement;

/**
 * Used to request a specific DimensionType from a certain office and code.
 *
 * @package PhpTwinfield
 * @subpackage Request
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 * @version 0.0.1
 */
class DimensionType extends DOMDocument
{
    /**
     * Holds the <dimensiontype> element that all
     * additional elements should be a child of.
     *
     * @access private
     * @var DOMElement
     */
    private $dimensionTypeElement;

    /**
     * Creates the <dimensiontype> element and adds it to the property
     * dimensionTypeElement and sets office and code if they are present.
     *
     * @access public
     */
    public function __construct($office = null, $code = null)
    {
        parent::__construct();

        $this->dimensionTypeElement = $this->createElement('dimensiontype');
        $this->appendChild($this->dimensionTypeElement);

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Adds additional elements to the <dimensiontype> dom element.
     *
     * See the documentation over what <dimensiontype> requires to know
     * and what additional elements you need.
     *
     * @access protected
     * @param string $element
     * @param mixed $value
     * @return void
     */
    protected function add($element, $value)
    {
        $_element = $this->createElement($element, $value);
        $this->dimensionTypeElement->appendChild($_element);
    }

    /**
     * Sets the office code for this request.
     *
     * @access public
     * @param int $office
     * @return \PhpTwinfield\Request\DimensionType
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this request.
     *
     * @access public
     * @param string $code
     * @return \PhpTwinfield\Request\DimensionType
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}
