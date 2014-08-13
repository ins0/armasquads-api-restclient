<?php

abstract class AbstractApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var \ArmAsquads\Api  */
    public $api = null;

    /**
     * @param $responseResults
     * @param int $responseCode
     * @param bool $isSuccess
     * @return \ArmAsquads\Api
     */
    public function getApi($responseResults, $responseCode = 200,$isSuccess = true )
    {
        return new \ArmAsquads\Api(
            $this->getAuthMock(),
            $this->getClientMock($responseResults, $responseCode, $isSuccess)
        );
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getAuthMock()
    {
        $authMock = $this->getMock('ArmAsquads\Api\Authentication\AuthenticationInterface', array('getCredential'));
        $authMock->expects($this->any())
            ->method('getCredential')
            ->will($this->returnValue('testToken'));
        return $authMock;
    }

    /**
     * @param $responseResults
     * @param int $responseCode
     * @param bool $isSuccess
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getClientMock($responseResults, $responseCode = 200, $isSuccess = true)
    {
        if( $isSuccess )
        {
            $responseBody = array(
                'success' => true,
                'results' => $responseResults
            );
        } else {
            $responseBody = array(
                'success' => false,
                'error' => array(
                    'testError'
                )
            );
        }

        $clientMock = $this->getMock('ArmAsquads\Api\Client\ClientInterface', array('sendRequest','getResponseStatusCode','setResponseStatusCode'));
        $clientMock->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue(json_encode($responseBody)));
        $clientMock->expects($this->any())
            ->method('getResponseStatusCode')
            ->will($this->returnValue($responseCode));
        $clientMock->expects($this->any())
            ->method('setResponseStatusCode');
        return $clientMock;
    }

}