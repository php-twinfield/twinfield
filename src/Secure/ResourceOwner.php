<?php

namespace PhpTwinfield\Secure;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    private $response;

    public function __construct(array $response)
    {
        print_r($response);
        $this->response = $response;
    }

    /**
     * Returns the identifier of the authorized resource owner.
     */
    public function getId(): string
    {
        return 'TODO';
    }

    /**
     * Return all of the owner details available as an array.
     */
    public function toArray(): array
    {
        return $this->response;
    }
}