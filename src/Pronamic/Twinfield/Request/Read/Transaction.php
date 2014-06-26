<?php
namespace Pronamic\Twinfield\Request\Read;

/**
 * Used to request a specific transaction from a certain
 * office, code and number.
 * 
 * @package Pronamic\Twinfield
 * @subpackage Request\Read
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Transaction extends Read
{
    /**
     * Sets the <type> to transaction for the request and
     * sets the office, code and number if they are present.
     * 
     * @access public
     * @param int $office
     * @param string $code
     * @param int $number
     */
    public function __construct($office = null, $code = null, $number = null)
    {
        parent::__construct();
        
        $this->add('type', 'transaction');
        
        if (null !== $office) {
            $this->setOffice($office);
        }
        
        if (null !== $code) {
            $this->setCode($code);
        }
        
        if (null !== $number) {
            $this->setNumber($number);
        }
    }
    
    /**
     * Sets the office code for this transaction request.
     * 
     * @access public
     * @param int $office
     * @return \Pronamic\Twinfield\Request\Read\Transaction
     */
    public function setOffice($office)
    {
        $this->add('office', $office);
        return $this;
    }
    
    /**
     * Sets the code for this transaction request.
     * 
     * @access public
     * @param string $code
     * @return \Pronamic\Twinfield\Request\Read\Transaction
     */
    public function setCode($code)
    {
        $this->add('code', $code);
        return $this;
    }
    
    /**
     * Sets the number for this transaction request
     * 
     * @access public
     * @param int $number
     * @return \Pronamic\Twinfield\Request\Read\Transaction
     */
    public function setNumber($number)
    {
        $this->add('number', $number);
        return $this;
    }
}
