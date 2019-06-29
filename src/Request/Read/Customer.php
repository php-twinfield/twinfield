<?php
namespace PhpTwinfield\Request\Read;

use PhpTwinfield\Office;

/**
 * Used to request a specific Customer from a certain
 * office and code.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class Customer extends Read
{
    /**
     * Sets the <type> to dimensions for the request and
     * sets the dimtype, office and code if they are present.
     *
     * @access public
     * @param Office|null $office
     * @param string $code
     */
    public function __construct(?Office $office = null, $code = null)
    {
        parent::__construct();

        $this->add('type', 'dimensions');
        $this->add('dimtype', 'DEB');

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }
    }
}
