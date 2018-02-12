<?php

namespace PhpTwinfield\Response;

use PhpTwinfield\Exception;

class ResponseException extends Exception
{
    /**
     * @var object
     */
    private $returnedObject;

    /**
     * @param object $returnedObject
     * @param Exception $previous
     */
    public function __construct($returnedObject, Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);

        $this->returnedObject = $returnedObject;
    }

    /**
     * @return object
     */
    public function getReturnedObject()
    {
        return $this->returnedObject;
    }
}