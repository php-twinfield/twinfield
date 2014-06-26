<?php
namespace Pronamic\Twinfield\Request\Catalog;

/**
 * Used to request a list of information about dimensions
 * from a certain office code and dimtype.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Request\Catalog
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Dimension extends Catalog
{
    /**
     * Sets the <type> to dimensions for the request
     * and sets the office and dimtype if they are passed
     * in.
     * 
     * @access public
     * @param string $office
     * @param string $dimType
     */
    public function __construct($office = null, $dimType = null)
    {
        parent::__construct();

        $this->add('type', 'dimensions');
        
        if (null !== $office) {
            $this->setOffice($office);
        }
        
        if (null !== $dimType) {
            $this->setDimType($dimType);
        }
    }

    /**
     * Sets the officecode for the dimension request.
     * 
     * @access public
     * @param int $office
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
    }

    /**
     * Sets the dimtype for the request.
     * 
     * @access public
     * @param string $dimType
     */
    public function setDimType($dimType)
    {
        $this->add('dimtype', $dimType);
    }
}
