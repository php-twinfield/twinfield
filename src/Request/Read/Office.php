<?php
namespace PhpTwinfield\Request\Read;

/**
 * Used to request a specific Office from a certain code.
 *
 * @package PhpTwinfield
 * @subpackage Request\Read
 * @author Yannick Aerssens <y.r.aerssens@gmail.com>
 * @version 0.0.1
 */
class Office extends Read
{
    /**
     * Sets office and code if they are present.
     *
     * @access public
     */
    public function __construct($code = null)
    {
        parent::__construct();

        $this->add('type', 'office');

        if (null !== $code) {
            $this->setCode($code);
        }
    }
}
