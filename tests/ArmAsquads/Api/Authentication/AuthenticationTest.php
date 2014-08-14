<?php
require_once(dirname(__FILE__) . '/../AbstractApiTest.php');

class AuthenticationTest extends AbstractApiTest
{
    public function testHttpHeaderAuthentication()
    {
        $auth = new \ArmAsquads\Api\Authentication\HttpHeader('testToken');
        $this->assertEquals('X-API-KEY: testToken', $auth->getCredential());
    }
}