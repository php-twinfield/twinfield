<?php
namespace PhpTwinfield\Request\Read;

use PhpTwinfield\Office;

/**
 * Used to request a specific Rate from a certain office and code.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 * @version 0.0.1
 */
class Rate extends Read
{
    /**
     * Sets office and code if they are present.
     *
     * @access public
     * @param Office $office
     * @param string $code
     */
    public function __construct(?Office $office = null, $code = null)
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
}
