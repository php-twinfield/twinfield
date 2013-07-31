<?php
namespace Pronamic\Twinfield\Request\Catalog;

/**
 * Used to request a list offices
 * 
 * @package Pronamic\Twinfield
 * @subpackage Request\Catalog
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Office extends Catalog
{
    /**
     * Adds the only required element for this request.
     * 
     * No other methods exist or are required,
     * 
     * @access public
     */
    public function __construct()
    {
        $this->add('type', 'offices');
    }
}
