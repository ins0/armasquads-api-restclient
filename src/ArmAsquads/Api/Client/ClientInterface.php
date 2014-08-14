<?php
namespace ArmAsquads\Api\Client;

use ArmAsquads\Api\Authentication\AuthenticationInterface;

/**
 * Interface ClientInterface
 *
 * @author      Marco Rieger
 * @copyright   Copyright (c) 2013 Marco Rieger (http://racecore.de)
 * @package     ArmAsquads\Api\Client
 */
interface ClientInterface
{
    public function sendRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication);
    public function getResponseStatusCode();
    public function setResponseStatusCode($statusCode);
}