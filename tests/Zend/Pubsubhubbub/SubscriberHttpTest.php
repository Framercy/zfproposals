<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Pubsubhubbub/Subscriber.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Client/Adapter/Socket.php';
require_once 'Zend/Uri/Http.php';

/**
 * Note that $this->_baseuri must point to a directory on a web server
 * containing all the files under the _files directory. You should symlink
 * or copy these files and set '_baseuri' properly using the constant in
 * TestConfiguration.php (based on TestConfiguration.php.dist)
 *
 * You can also set the proper constant in your test configuration file to
 * point to the right place.
 */


class Zend_Pubsubhubbub_SubscriberHttpTest extends PHPUnit_Framework_TestCase
{

    protected $_subscriber = null;

    protected $_baseuri;

    protected $_client = null;

    protected $_adapter = null;

    protected $_config = array(
        'adapter'     => 'Zend_Http_Client_Adapter_Socket'
    );

    public function setUp()
    {
        if (defined('TESTS_ZEND_PUBSUBHUBBUB_BASEURI') &&
            Zend_Uri_Http::check(TESTS_ZEND_PUBSUBHUBBUB_BASEURI)) {
            $this->_baseuri = TESTS_ZEND_PUBSUBHUBBUB_BASEURI;
            if (substr($this->_baseuri, -1) != '/') $this->_baseuri .= '/';

            $name = $this->getName();
            if (($pos = strpos($name, ' ')) !== false) {
                $name = substr($name, 0, $pos);
            }

            $uri = $this->_baseuri . $name . '.php';

            $this->_adapter = new $this->_config['adapter'];
            $this->_client = new Zend_Http_Client($uri, $this->_config);
            $this->_client->setAdapter($this->_adapter);

            Zend_Pubsubhubbub::setHttpClient($this->_client);
            $this->_subscriber = new Zend_Pubsubhubbub_Subscriber;
            $this->_subscriber->setStorage(new Zend_Pubsubhubbub_Storage_Filesystem);

        } else {
            // Skip tests
            $this->markTestSkipped("Zend_Pubsubhubbub_Subscriber dynamic tests'
            . ' are not enabled in TestConfiguration.php");
        }
    }

    public function testSubscriptionRequestSendsExpectedPostData()
    {
        $this->_subscriber->setTopicUrl('http://www.example.com/topic');
        $this->_subscriber->addHubUrl($this->_baseuri . '/testRawPostData.php');
        $this->_subscriber->setCallbackUrl('http://www.example.com/callback');
        $this->_subscriber->setTestStaticToken('abc'); // override for testing
        $this->_subscriber->subscribeAll();
        $this->assertEquals(
            'hub.callback=http%3A%2F%2Fwww.example.com%2Fcallback%2F8c01a802e4a'
            .'c9beebccc36b71f240e4b89f273d4&hub.lease_seconds=2592000&hub.mode='
            .'subscribe&hub.topic=http%3A%2F%2Fwww.example.com%2Ftopic&hub.veri'
            .'fy=sync&hub.verify=async&hub.verify_token=abc',
            $this->_client->getLastResponse()->getBody());
    }

    public function testUnsubscriptionRequestSendsExpectedPostData()
    {
        $this->_subscriber->setTopicUrl('http://www.example.com/topic');
        $this->_subscriber->addHubUrl($this->_baseuri . '/testRawPostData.php');
        $this->_subscriber->setCallbackUrl('http://www.example.com/callback');
        $this->_subscriber->setTestStaticToken('abc'); //override for testing
        $this->_subscriber->unsubscribeAll();
        $this->assertEquals(
            'hub.callback=http%3A%2F%2Fwww.example.com%2Fcallback%2Ff92d0e925da'
            .'366ac8ee5efc6a01b90e2672111f1&hub.mode=unsubscribe&hub.topic=http'
            .'%3A%2F%2Fwww.example.com%2Ftopic&hub.verify=sync&hub.verify=async'
            .'&hub.verify_token=abc',
            $this->_client->getLastResponse()->getBody());
    }

}