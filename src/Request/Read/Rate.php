<?php
namespace PhpTwinfield\Request\Read;

/**
 * Used to request a specific Rate from a certain office and code.
 * 
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>, based on Article by Willem van de Sande <W.vandeSande@MailCoupon.nl>
 * @version 0.0.1
 */
class Rate extends Read
{
    /**
     * Sets office and code if they are present.
     * 
     * @access public
     */
    public function __construct($office = null, $code = null)
    {
        parent::__construct();

        $this->add('type', 'projectrate');
        
        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }

    /**
     * Sets the office code for this Rate request.
     * 
     * @access public
     * @param int $office
     * @return \PhpTwinfield\Request\Read\Rate
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }

    /**
     * Sets the code for this Rate request.
     * 
     * @access public
     * @param string $code
     * @return \PhpTwinfield\Request\Read\Rate
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
}