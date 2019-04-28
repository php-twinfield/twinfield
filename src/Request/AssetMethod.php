<?php
namespace PhpTwinfield\Request;

use DOMDocument;

/**
 * Used to request a specific AssetMethod from a certain office and code.
 *
 * @package PhpTwinfield
 * @subpackage Request
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on Article by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 * @version 0.0.1
 */
class AssetMethod extends DOMDocument
{
    /**
     * Holds the <assetmethod> element that all
     * additional elements should be a child of.
     *
     * @access private
     * @var DOMElement
     */
    private $assetMethodElement;

    /**
     * Creates the <assetmethod> element and adds it to the property
     * assetMethodElement and sets office and code if they are present.
     *
     * @access public
     */
    public function __construct($office = null, $code = null)
    {
        parent::__construct();

        $this->assetMethodElement = $this->createElement('assetmethod');
        $this->appendChild($this->assetMethodElement);

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Adds additional elements to the <assetmethod> dom element.
     *
     * See the documentation over what <assetmethod> requires to know
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
        $this->assetMethodElement->appendChild($_element);
    }

    /**
     * Sets the office code for this AssetMethod request.
     *
     * @access public
     * @param int $office
     * @return \PhpTwinfield\Request\Read\AssetMethod
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this AssetMethod request.
     *
     * @access public
     * @param string $code
     * @return \PhpTwinfield\Request\Read\AssetMethod
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}
