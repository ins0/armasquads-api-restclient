<?php
require_once(dirname(__FILE__) . '/../AbstractApiTest.php');

class ClientTest extends AbstractApiTest
{

    public function testGetAndSetClient()
    {
        $api = $this->getApi(null);
        $api->setClient(new \ArmAsquads\Api\Client\CurlClient());

        $this->assertInstanceOf('ArmAsquads\Api\Client\CurlClient', $api->getClient());
    }

    public function testLastStatusCode()
    {
        $client = new \ArmAsquads\Api\Client\CurlClient();
        $client->setResponseStatusCode(666);

        $this->assertSame(666, $client->getResponseStatusCode());
    }
}