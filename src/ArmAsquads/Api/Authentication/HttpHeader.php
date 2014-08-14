<?php
namespace ArmAsquads\Api\Authentication;

/**
 * Class HttpHeader
 *
 * @author      Marco Rieger
 * @copyright   Copyright (c) 2013 Marco Rieger (http://racecore.de)
 * @package     ArmAsquads\Api\Authentication
 */
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