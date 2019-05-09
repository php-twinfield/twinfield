<?php
namespace PhpTwinfield\Request\Read;

/**
 * Used to request a specific GeneralLedger from a certain office and code.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 * @version 0.0.1
 */
class GeneralLedger extends Read
{
    /**
     * Sets office and code if they are present.
     *
     * @access public
     */
    public function __construct($office = null, $code = null, $dimType = null)
    {
        parent::__construct();

        $this->add('type', 'dimensions');

        if (null !== $office) {
            $this->setOffice($office);
        }

        if (null !== $code) {
            $this->setCode($code);
        }

        if (null !== $dimType) {
            $this->setDimType($dimType);
        }
    }
}