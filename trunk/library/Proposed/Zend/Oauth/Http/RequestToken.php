<?php

require_once 'Zend/Oauth/Http.php';
require_once 'Zend/Oauth/Token/Request.php';

class Zend_Oauth_Http_RequestToken extends Zend_Oauth_Http
{

    protected $_httpClient = null;

    public function execute()
    {
        $params = $this->assembleParams();
        $response = $this->startRequestCycle($params);
        $return = new Zend_Oauth_Token_Request($response);
        return $return;
    }

    public function assembleParams()
    {
        $params = array();
        // Google fix (don't ask)
        //if (preg_match("%https\:\/\/www\.google\.com\/accounts\/OAuthGet%", //$this->_consumer->getRequestTokenUrl())
        //    && $this->_consumer->getRequestMethod() == 'POST') {
        //    $params[''] = ''; // we can haz empty params not in spec
        //}
        $params['oauth_consumer_key'] = $this->_consumer->getConsumerKey();
        $params['oauth_nonce'] = $this->_httpUtility->generateNonce();
        $params['oauth_signature_method'] = $this->_consumer->getSignatureMethod();
        $params['oauth_timestamp'] = $this->_httpUtility->generateTimestamp();
        $params['oauth_version'] = $this->_consumer->getVersion();
        if (!empty($this->_parameters)) {
            $params = array_merge($params, $this->_parameters);
        }
        $params['oauth_signature'] = $this->_httpUtility->sign(
            $params,
            $this->_consumer->getSignatureMethod(),
            $this->_consumer->getConsumerSecret(),
            null,
            $this->_preferredRequestMethod,
            $this->_consumer->getRequestTokenUrl()
        );
        return $params;
    }

    public function getRequestSchemeHeaderClient(array $params)
    {
        $headerValue = $this->_httpUtility->toAuthorizationHeader(
            $params, null, $this->_excludeParamsFromHeader
        );
        $client = Zend_Oauth::getHttpClient();
        $client->setUri($this->_consumer->getRequestTokenUrl());
        $client->setHeaders('Authorization', $headerValue);
        if ($this->_excludeParamsFromHeader) {
            $rawdata = $this->_httpUtility->toEncodedQueryString($params, true);
            if (!empty($rawdata)) $client->setRawData($rawdata);
        }
        $client->setMethod(Zend_Http_Client::POST);
        return $client;
    }

    public function getRequestSchemePostBodyClient(array $params)
    {
        $client = Zend_Oauth::getHttpClient();
        $client->setUri($this->_consumer->getRequestTokenUrl());
        $client->setMethod(Zend_Http_Client::POST);
        $client->setRawData(
            $this->_httpUtility->toEncodedQueryString($params)
        );
        return $client;
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

}