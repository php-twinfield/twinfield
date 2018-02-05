<?php

namespace PhpTwinfield\Response;

use PhpTwinfield\Exception;

class IndividualMappedResponse
{
    /**
     * @var Response
     */
    private $response;
    /**
     * @var callable
     */
    private $mapper;

    public function __construct(Response $response, callable $mapper)
    {
        $this->response = $response;
        $this->mapper = $mapper;
    }

    public function isSuccessful(): bool
    {
        try {
            $this->response->assertSuccessful();
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @throws \PhpTwinfield\Exception
     * @return object
     */
    public function unwrap()
    {
        $this->response->assertSuccessful();
        return \call_user_func($this->mapper, $this->response);
    }
}