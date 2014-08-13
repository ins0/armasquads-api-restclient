<?php
namespace ArmAsquads\Api\Client;

use ArmAsquads\Api\Authentication\AuthenticationInterface;
use ArmAsquads\Api\Exception;

class CurlClient implements ClientInterface
{
    /** @var  StatusCode */
    private $lastResponseStatusCode;

    public function __construct()
    {
        if( !function_exists('curl_init') )
        {
            throw new Exception('curl lib functions not found');
        }
    }

    /**
     * Send Request
     *
     * @param $method
     * @param array $data
     * @param $endpoint
     * @param AuthenticationInterface $authentication
     * @return mixed
     * @throws \ArmAsquads\Api\Exception
     */
    public function sendRequest($method, $data = array(), $endpoint, AuthenticationInterface $authentication)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array($authentication->getCredential()));

        if( $data )
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $sResult = curl_exec($ch);
        if (curl_errno($ch))
        {
            throw new Exception(curl_error($ch));
        }

        $this->lastResponseStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $sResult;
    }

    /**
     * Return last HTTP Status Code
     *
     * @return StatusCode
     */
    public function getResponseStatusCode()
    {
        return $this->lastResponseStatusCode;
    }

    /**
     * Set last HTTP Status Code
     *
     * @param $statusCode
     * @return $this
     */
    public function setResponseStatusCode($statusCode)
    {
        $this->lastResponseStatusCode = $statusCode;
        return $this;
    }
}