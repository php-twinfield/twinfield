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
            $this->assertSuccessful();
        } catch (ResponseException $e) {
            return false;
        }
        return true;
    }

    /**
     * @throws ResponseException
     */
    public function assertSuccessful(): void
    {
        try {
            $this->response->assertSuccessful();
        } catch (Exception $e) {
            throw new ResponseException($this->mapResponse(), $e);
        }
    }

    /**
     * @throws ResponseException
     * @return object
     */
    public function unwrap()
    {
        $this->assertSuccessful();

        return $this->mapResponse();
    }

    /**
     * @return object
     */
    private function mapResponse()
    {
        return \call_user_func($this->mapper, $this->response);
    }

    /**
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return $this->response->getErrorMessages();
    }
}