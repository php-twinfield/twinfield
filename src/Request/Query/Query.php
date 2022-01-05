<?php
namespace PhpTwinfield\Request\Query;

/**
 * Abstract parent class Query. Query is the name for the request component
 * QUERY.
 *
 * All aspects of QUERY request require a parent element called 'query'
 *
 * The construct makes this element, appends to itself. All requirements to
 * add new elements to this <query> dom element are done through the add()
 * method.
 *
 * @package PhpTwinfield
 * @subpackage Request\Query
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
abstract class Query
{
    abstract protected function TYPE(): string;

    abstract protected function SERVICE(): string;

    private ?\stdClass $parameters = null;

    /**
     * Creates the <Query> element and adds it to the property
     * queryElement
     *
     * @access public
     */
    public function __construct()
    {
        $this->parameters = new \stdClass();
    }

    /**
     * Adds additional elements to the <query> element.
     *
     * See the documentation over what <query> requires to know
     * and what additional elements you need.
     *
     * @access protected
     * @param string $element
     * @param mixed $value
     * @return void
     */
    protected function add($element, $value)
    {
        $this->parameters->{$element} = $value;
    }

    public function getParameters()
    {
        return new \SoapVar( $this->parameters, SOAP_ENC_OBJECT, $this->TYPE(), $this->SERVICE() );
    }
}
