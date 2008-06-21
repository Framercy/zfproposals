<?php

require_once 'Zend/Oauth/Http.php';
require_once 'Zend/Oauth/Token/Access.php';

class Zend_Oauth_Http_AccessToken extends Zend_Oauth_Http
{

    protected $_httpClient = null;

    public function execute(array $params = null)
    {
        $params = $this->assembleParams($params);
        $response = $this->startRequestCycle($params);
        $return = new Zend_Oauth_Token_Access($response);
        return $return;
    }

    public function assembleParams()
    {
        $params = array();
        $params['oauth_consumer_key'] = $this->_consumer->getConsumerKey();
        $params['oauth_nonce'] = $this->_consumer->generateNonce();
        $params['oauth_signature_method'] = $this->_consumer->getSignatureMethod();
        $params['oauth_timestamp'] = $this->_consumer->generateTimestamp();
        $params['oauth_token'] = $this->_consumer->getLastRequestToken()->getToken();
        $params['oauth_version'] = $this->_consumer->getVersion();
        if (!empty($this->_parameters)) {
            $params = array_merge($params, $this->_parameters);
        }
        $params['oauth_signature'] = $this->_consumer->sign(
            $params,
            $this->_consumer->getSignatureMethod(),
            $this->_consumer->getConsumerSecret(),
            null,
            $this->_consumer->getRequestMethod(), //should change with scheme changes!!!
            $this->_consumer->getAccessTokenUrl()
        );
        return $params;
    }

    public function getRequestSchemeHeaderClient(array $params)
    {
        $headerValue = $this->_toAuthorizationHeader($params);
        $client = Zend_Oauth::getHttpClient();
        $client->setUri($this->_consumer->getRequestTokenUrl());
        $client->setHeaders('Authorization', $headerValue);
        return $client;
    }

    public function getRequestSchemePostBodyClient(array $params)
    {
        $client = Zend_Oauth::getHttpClient();
        $client->setUri($this->_consumer->getRequestTokenUrl());
        $encodedParams = array();
        foreach ($params as $key => $value) {
            $encodedParams[] =
                Zend_Oauth::urlEncode($key) . '=' . Zend_Oauth::urlEncode($value);
        }
        $client->setMethod(Zend_Http_Client::POST);
        $client->setRawData(implode('&', $encodedParams));
        return $client;
    }

    public function startRequestCycle(array $params)
    {
        $response = null;
        $body = null;
        $status = null;
        try {
            $response = $this->_attemptRequest($params);
        } catch (Zend_Http_Client_Exception $e) {
        }
        if (!is_null($response)) {
            $body = $response->getBody();
            $status = $response->getStatus();
        }
        if (is_null($response)// Request failure/exception
            || $status == 500 // Internal Server Error
            || $status == 400 // Bad Request
            || $status == 401 // Unauthorized
            || empty($body)   // Missing request token
            ) {
            $this->_assessRequestAttempt();
            $response = $this->startRequestCycle($params);
        }
        return $response;
    }

    protected function _assessRequestAttempt()
    {
        switch ($this->_preferredRequestScheme) {
            case Zend_Oauth::REQUEST_SCHEME_HEADER:
                $this->_preferredRequestScheme = Zend_Oauth::REQUEST_SCHEME_POSTBODY;
                break;
            case Zend_Oauth::REQUEST_SCHEME_POSTBODY:
                $this->_preferredRequestScheme = Zend_Oauth::REQUEST_SCHEME_QUERYSTRING;
                break;
            default:
                require_once 'Zend/Oauth/Exception.php';
                throw new Zend_Oauth_Exception(
                    'Could not retrieve a valid Access Token response from Access Token URL'
                );
        }
    }

    protected function _attemptRequest(array $params)
    {
        switch ($this->_preferredRequestScheme) {
            case Zend_Oauth::REQUEST_SCHEME_HEADER:
                $httpClient = $this->getRequestSchemeHeaderClient($params);
                break;
            case Zend_Oauth::REQUEST_SCHEME_POSTBODY:
                $httpClient = $this->getRequestSchemePostBodyClient($params);
                break;
            case Zend_Oauth::REQUEST_SCHEME_QUERYSTRING:
                $httpClient = $this->getRequestSchemeQueryStringClient($params,
                    $this->_consumer->getRequestTokenUrl());
                break;
        }
        return $httpClient->request();
    }

    protected function _toAuthorizationHeader(array $params, $realm = null)
    {
        $headerValue = array();
        if (is_null($realm)) {
            $headerValue[] = 'OAuth';
        } else {
            $headerValue[] = 'OAuth realm="' . $realm . '"';
        }
        foreach ($params as $key => $value) {
            $headerValue[] =
                Zend_Oauth::urlEncode($key)
                . '="'
                . Zend_Oauth::urlEncode($value)
                . '"';
        }
        return implode(",", $headerValue);
    }

}