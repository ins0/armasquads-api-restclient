<?php
require_once(dirname(__FILE__) . '/AbstractApiTest.php');

class ApiTest extends AbstractApiTest
{

    public function testDefaultClientOnConstruct()
    {
        $api = new \ArmAsquads\Api($this->getAuthMock());

        $this->assertInstanceOf('ArmAsquads\Api\Client\CurlClient', $api->getClient());
    }

    public function testGetAndSetAuthentication()
    {
        $api = $this->getApi(null);
        $api->setAuthentication(new \ArmAsquads\Api\Authentication\HttpHeader('testToken'));

        $this->assertInstanceOf('ArmAsquads\Api\Authentication\AuthenticationInterface', $api->getAuthentication());
    }

    /**
     * @expectedException \ArmAsquads\Api\Exception\RequestFailedException
     */
    public function testApiRequestFailedException()
    {
        $api = $this->getApi(null,400,false);
        $api->deleteSquad(1);
    }

    /**
     * @expectedException \ArmAsquads\Api\Exception\InvalidResponseException
     */
    public function ApiInvalidResponseException()
    {
        $api = $this->getApi(null);
        $api->getClient()->expects($this->any())->method('sendRequest')->will($this->returnValue('notJson'));
        $api->deleteSquad(1);
    }

    public function testGetAndSetApiErrorMessages()
    {
        $api = $this->getApi(null);
        $api->setResponseErrorMessages(array('foo', 'bar', 'baz'));

        $this->assertSame(array('foo', 'bar', 'baz'), $api->getResponseErrorMessages());
    }
}