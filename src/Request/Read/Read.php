<?php
namespace PhpTwinfield\Request\Read;

use PhpTwinfield\Office;

/**
 * Abstract parent class Read. Read is the name for the request component
 * READ.
 *
 * All aspects of READ request require a parent element called 'read'
 *
 * The construct makes this element, appends to itself. All requirements to
 * add new elements to this <read> dom element are done through the add()
 * method.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
abstract class Read extends \DOMDocument
{
    /**
     * Holds the <read> element that all
     * additional elements should be a child of.
     *
     * @access private
     * @var \DOMElement
     */
    private $readElement;

    /**
     * Creates the <read> element and adds it to the property
     * readElement
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->readElement = $this->createElement('read');
        $this->appendChild($this->readElement);
    }

    /**
     * Adds additional elements to the <read> dom element.
     *
     * See the documentation over what <read> requires to know
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
        $this->readElement->appendChild($_element);
    }

    /**
     * Sets the office code for this request.
     *
     * @access public
     * @param Office $office
     * @return \PhpTwinfield\Request\Read\Read
     */
    public function setOffice(Office $office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this request.
     *
     * @access public
     * @param string $code
     * @return \PhpTwinfield\Request\Read\Read
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }

    /**
     * Sets the dimtype for this request.
     *
     * @access public
     * @param string $dimType
     * @return \PhpTwinfield\Request\Read\Read
     */
    public function setDimType($dimType)
    {
        $this->add('dimtype', $dimType);
        return $this;
    }

    /**
     * Sets the number for this request.
     *
     * @access public
     * @param string $number
     * @return \PhpTwinfield\Request\Read\Read
     */
    public function setNumber($number)
    {
        $this->add('number', $number);
        return $this;
    }
}
