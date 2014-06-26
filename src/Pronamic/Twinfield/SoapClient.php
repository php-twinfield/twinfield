<?php
namespace Pronamic\Twinfield;

/**
 * Twinfield Soap Client.
 * 
 * @todo find a nice way to handle a possible timeout
 * 
 * @package Pronamic\Twinfield
 * @author Leon Rowland <leon@rowland.nl>
 * @copyright (c) 2013, Pronamic
 * @version 0.0.1
 */
class SoapClient extends \SoapClient
{
    /**
     * Overides the call method, to keep making
     * requests if it times out.
     * 
     * @todo require a better way than using exceptions.
     * 
     * @access public
     * @param string $function_name
     * @param mixed $arguments
     * @return SoapClient
     * @throws \SoapFault
     */
    public function __call($function_name, $arguments)
    {
        $result      = false;
        $max_retries = 5;
        $retry_count = 0;

        // Keep making the same request until you have reached 5 attempts.
        while (! $result && $retry_count < $max_retries) {
            try {
                $result = parent::__call($function_name, $arguments);
            } catch (SoapFault $fault) {
                sleep(1);
                $retry_count++;
            }
        }

        // Throw the error after 5 attempts
        if ($retry_count == $max_retries) {
            throw new \SoapFault('Failed after 5 attempts');
        }
        
        return $result;
    }
}
