<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Oauth/Http/RequestToken.php';

class Zend_Oauth_Http_RequestTokenTest extends PHPUnit_Framework_TestCase
{

    protected $stubConsumer = null;

    public function setup()
    {
        $this->stubConsumer = new Test_Consumer_32874;
        Zend_Oauth::setHttpClient(new Test_Client_32874);
    }

    public function testConstructorSetsConsumerInstance()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer);
        $this->assertType('Test_Consumer_32874', $request->getConsumer());
    }

    public function testConstructorSetsCustomServiceParameters()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer, array(1,2,3));
        $this->assertEquals(array(1,2,3), $request->getParameters());
    }

    public function testAssembleParametersCorrectlyAggregatesOauthParameters()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer);
        $expectedParams = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c'
        );
        $this->assertEquals($expectedParams, $request->assembleParams());
    }
    public function testAssembleParametersCorrectlyAggregatesCustomParameters()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer, array(
            'custom_param1'=>'foo',
            'custom_param2'=>'bar'
        ));
        $expectedParams = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $this->assertEquals($expectedParams, $request->assembleParams());
    }

    public function testGetRequestSchemeHeaderClientSetsCorrectlyEncodedAuthorizationHeader()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c~',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemeHeaderClient($params);
        $this->assertEquals(
        'OAuth,oauth_consumer_key="1234567890",oauth_nonce="e807f1fcf82d132f9b'
        .'b018ca6738a19f",oauth_signature_method="HMAC-SHA1",oauth_timestamp="'
        .'12345678901",oauth_version="1.0",oauth_signature="6fb42da0e32e07b61c'
        .'9f0251fe627a9c~",custom_param1="foo",custom_param2="bar"',
            $client->getHeader('Authorization')
        );
    }

    public function testGetRequestSchemePostBodyClientSetsCorrectlyEncodedRawData()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_signature' => '6fb42da0e32e07b61c9f0251fe627a9c~',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemePostBodyClient($params);
        $this->assertEquals(
            'oauth_consumer_key=1234567890&oauth_nonce=e807f1fcf82d132f9bb018c'
            .'a6738a19f&oauth_signature_method=HMAC-SHA1&oauth_timestamp=12345'
            .'678901&oauth_version=1.0&oauth_signature=6fb42da0e32e07b61c9f025'
            .'1fe627a9c~&custom_param1=foo&custom_param2=bar',
            $client->getRawData()
        );
    }

    public function testGetRequestSchemeQueryStringClientSetsCorrectlyEncodedQueryString()
    {
        $request = new Zend_Oauth_Http_RequestToken($this->stubConsumer);
        $params = array (
            'oauth_consumer_key' => '1234567890',
            'oauth_nonce' => 'e807f1fcf82d132f9bb018ca6738a19f',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '12345678901',
            'oauth_version' => '1.0',
            'oauth_signature' => ' 6fb42da0e32e07b61c9f0251fe627a9c~',
            'custom_param1' => 'foo',
            'custom_param2' => 'bar'
        );
        $client = $request->getRequestSchemeQueryStringClient($params);
        $this->assertEquals(
            'oauth_consumer_key=1234567890&oauth_nonce=e807f1fcf82d132f9bb018c'
            .'a6738a19f&oauth_signature_method=HMAC-SHA1&oauth_timestamp=12345'
            .'678901&oauth_version=1.0&oauth_signature=+6fb42da0e32e07b61c9f025'
            .'1fe627a9c~&custom_param1=foo&custom_param2=bar',
            $client->getUri()->getQuery()
        );
    }

}

class Test_Consumer_32874 extends Zend_Oauth_Consumer
{
    public function __construct(){}
    public function getConsumerKey(){return '1234567890';}
    public function generateNonce(){return md5('1234567890');}
    public function getSignatureMethod(){return 'HMAC-SHA1';}
    public function generateTimestamp(){return '12345678901';}
    public function getVersion(){return '1.0';}
    public function sign(array $params, $signatureMethod, $consumerSecret,
        $accessTokenSecret = null, $method = null, $url = null)
    {
        return md5('0987654321');
    }
    public function getRequestTokenUrl(){return 'http://www.example.com/request';}
}

class Test_Client_32874 extends Zend_Http_Client
{
    public function getRawData(){return $this->raw_post_data;}
}