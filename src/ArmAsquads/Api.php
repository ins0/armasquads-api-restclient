<?php
namespace ArmAsquads;

use ArmAsquads\Api\Authentication\AuthenticationInterface;
use ArmAsquads\Api\Client\ClientInterface;
use ArmAsquads\Api\Client\CurlClient;
use ArmAsquads\Api\Entity\Member;
use ArmAsquads\Api\Exception\InvalidResponseException;
use ArmAsquads\Api\Exception\RequestFailedException;
use ArmAsquads\Api\Entity\Squad;
use ArmAsquads\Api\Exception;

class Api
{
    CONST API_ENDPOINT      = 'https://armasquads.com/api';
    CONST API_VERSION       = 'v1';

    CONST REQUEST_POST      = 'POST';
    CONST REQUEST_PUT       = 'PUT';
    CONST REQUEST_DELETE    = 'DELETE';
    CONST REQUEST_GET       = 'GET';

    /** @var  AuthenticationInterface */
    private $authentication;

    /** @var  ClientInterface */
    private $client;
    private $responseErrorMessages = null;

    /**
     * Constructor
     *
     * @param AuthenticationInterface $authentication
     * @param ClientInterface $client
     */
    public function __construct(AuthenticationInterface $authentication, ClientInterface $client = null)
    {
        // set client
        if( $client === null )
        {
            $client = new CurlClient();
        }

        // set auth
        $this->setAuthentication($authentication);

        // set client
        $this->setClient($client);
    }

    /**
     * Delete a existing Squad by SquadId
     *
     * @param $squadId
     * @return bool
     */
    public function deleteSquad($squadId)
    {
        $this->callApi(self::REQUEST_DELETE, '/squads/' . $squadId);

        return $this->getLastResponseStatusCode() == 200 ? true : false;
    }

    /**
     * Update a existing Squad
     *
     * @param Squad $squad
     * @return bool
     * @throws Api\Exception
     */
    public function updateSquad(Squad &$squad)
    {
        if( !$squad->getId() )
            throw new Exception('squad id not provided');

        $response = $this->callApi(self::REQUEST_PUT, '/squads/' . $squad->getId(), $squad->getArrayCopy());
        $squad->exchangeArray($response);

        return $this->getLastResponseStatusCode() == 200 ? true : false;
    }

    /**
     * Create a new Squad
     *
     * @param Squad $squad
     * @return bool
     */
    public function createSquad(Squad &$squad)
    {
        $response = $this->callApi(self::REQUEST_POST, '/squads', $squad->getArrayCopy());
        $squad->exchangeArray($response);

        return $this->getLastResponseStatusCode() == 201 ? true : false;
    }

    /**
     * Return all User Squads
     *
     * @return array|mixed
     */
    public function getSquads()
    {
        $response = $this->callApi(self::REQUEST_GET, '/squads');

        if( !$response )
            return array();

        $squads = array_map(function($data){
            $squad = new Squad();
            return $squad->exchangeArray($data);
        }, $response);

        return $squads;
    }

    /**
     * Return Squad By SquadId
     *
     * @param $squadId
     * @return $this
     */
    public function getSquad($squadId)
    {
        $response = $this->callApi(self::REQUEST_GET, '/squads/' . $squadId);

        if( !$response )
            return false;

        $squad = new Squad();
        return $squad->exchangeArray($response);
    }

    /**
     * Delete a existing Member by SquadId
     *
     * @param $memberUuid
     * @param $squadId
     * @return bool
     */
    public function deleteMember($memberUuid, $squadId)
    {
        $this->callApi(self::REQUEST_DELETE, '/squads/' . $squadId . '/members/' . $memberUuid);

        return $this->getLastResponseStatusCode() == 200 ? true : false;
    }

    /**
     * Update a existing Member from Squad
     *
     * @param Member $member
     * @param $squadId
     * @return bool
     * @throws Api\Exception
     */
    public function updateMember(Member &$member, $squadId)
    {
        if( !$member->getUuid() )
            throw new Exception('member id not provided');

        $response = $this->callApi(self::REQUEST_PUT, '/squads/' . $squadId . '/members/' . $member->getUuid(), $member->getArrayCopy());
        $member->exchangeArray($response);

        return $this->getLastResponseStatusCode() == 200 ? true : false;
    }

    /**
     * Create a new Member in Squad
     *
     * @param Member $member
     * @param $squadId
     * @return bool
     */
    public function createMember(Member &$member, $squadId)
    {
        $response = $this->callApi(self::REQUEST_POST, '/squads/' . $squadId . '/members', $member->getArrayCopy());
        $member->exchangeArray($response);

        return $this->getLastResponseStatusCode() == 201 ? true : false;
    }

    /**
     * Return all Members from Squad
     *
     * @param $squadId
     * @return array|mixed
     */
    public function getMembers($squadId)
    {
        $response = $this->callApi(self::REQUEST_GET, '/squads/' . $squadId . '/members');

        if( !$response )
            return array();

        $members = array_map(function($data){
            $member = new Member();
            return $member->exchangeArray($data);
        }, $response);

        return $members;
    }

    /**
     * Return Member from Squad by MemberUUID
     *
     * @param $memberUuid
     * @param $squadId
     * @return $this
     */
    public function getMember($memberUuid, $squadId)
    {
        $response = $this->callApi(self::REQUEST_GET, '/squads/' . $squadId . '/members/' . $memberUuid);

        if( !$response )
            return false;

        $member = new Member();
        return $member->exchangeArray($response);
    }

    /**
     * Client Call to API
     *
     * @param string $method
     * @param $resource
     * @param array $data
     * @return mixed
     * @throws Api\Exception\InvalidResponseException
     * @throws Api\Exception\RequestFailedException
     */
    private function callApi($method = self::REQUEST_GET, $resource, $data = array())
    {
        // reset prev error
        $this->setResponseErrorMessages(null);

        // get client
        $client = $this->getClient();

        // send request
        $response = $client->sendRequest(
            $method,
            $data,
            sprintf('%s/%s%s', self::API_ENDPOINT, self::API_VERSION, $resource),
            $this->getAuthentication()
        );

        // decode response
        $json = json_decode($response, true);
        if( !$json )
        {
            throw new InvalidResponseException('API response invalid/malformed');
        } else {

            if( $json['success'] !== true )
            {
                $this->setResponseErrorMessages($json['error']);
                throw new RequestFailedException('API respond: request failed', $client->getResponseStatusCode());
            }
            return $json['results'];
        }
    }

    /**
     * Get last Error Messages from API Response
     *
     * @return array|null
     */
    public function getResponseErrorMessages()
    {
        return $this->responseErrorMessages;
    }

    /**
     * Set last Error Messages from API Response
     *
     * @param $messages
     * @return $this
     */
    public function setResponseErrorMessages($messages)
    {
        $this->responseErrorMessages = $messages;
        return $this;
    }

    /**
     * Get last Status Code from Client
     *
     * @return null
     */
    public function getLastResponseStatusCode()
    {
        return $this->getClient()->getResponseStatusCode();
    }

    /**
     * @param \ArmAsquads\Api\Authentication\AuthenticationInterface $authentication
     */
    public function setAuthentication(AuthenticationInterface $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * @return \ArmAsquads\Api\Authentication\AuthenticationInterface
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param \ArmAsquads\Api\Client\ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return \ArmAsquads\Api\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}