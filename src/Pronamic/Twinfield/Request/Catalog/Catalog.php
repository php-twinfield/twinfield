<?php
namespace Pronamic\Twinfield\Request\Catalog;

/**
 * Abstract parent class Catalog. Catalog is the name of the request
 * for LIST. List is a protected term in PHP so all instances of the word
 * catalog are just a replacement.
 * 
 * All aspects of LIST request require a parent element called 'list'
 * 
 * The constructor makes this element, appends to the itself.  All requirements
 * to add new elements to this <list> dom element are done through
 * the add() method.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Request\Catalog
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
abstract class Catalog extends \DOMDocument
{
    /**
     * Holds the <list> element that all
     * additional elements should be a child of.
     * 
     * @access private
     * @var \DOMElement
     */
    private $listElement;

    /**
     * Creates the <list> element and adds it to the property
     * listElement
     * 
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $this->listElement = $this->createElement('list');
        $this->appendChild($this->listElement);
    }

    /**
     * Adds additional elements to the <list> dom element.
     * 
     * See the documentation over what <list> requires to know
     * what additional elements you need.
     * 
     * @access protected
     * @param string $element
     * @param mixed $value
     * @return void
     */
    protected function add($element, $value)
    {
        $_element = $this->createElement($element, $value);
        $this->listElement->appendChild($_element);
    }
}
