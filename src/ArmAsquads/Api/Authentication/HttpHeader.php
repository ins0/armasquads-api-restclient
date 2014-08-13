<?php
namespace ArmAsquads\Api\Authentication;

class HttpHeader implements AuthenticationInterface
{
    private $accessToken = null;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getCredential()
    {
        return 'X-API-KEY: ' . $this->accessToken;
    }
}