<?php

require_once 'Zend/Feed/Reader.php';

/**
 * Interpretive class for Zend_Feed which interprets incoming
 * Zend_Feed_Abstract objects and presents a common unified API for all RSS
 * and Atom versions.
 * Or will...when it's been completed ;).
 *
 * @copyright 2007-2008 Pádraic Brady (http://blog.astrumfutura.com)
 */
abstract class Zend_Feed_Reader_Feed extends Zend_Feed_Reader
{

    protected $_feed = null;

    protected $_data = array();

    protected $_domDocument = null;

    protected $_xpath = null;

    public function __construct(Zend_Feed_Abstract $feed, $type = null)
    {
        $this->_feed = $feed;
        $this->_domDocument = $feed->getDOM()->ownerDocument;
        $this->_xpath = new DOMXPath($this->_domDocument);
        if (!is_null($type)) {
            $this->_data['type'] = $type;
        } else {
            $this->_data['type'] = self::detectType($feed);
        }
        $this->_registerDefaultNamespaces();
    }

    public function getType()
    {
        return $this->_data['type'];
    }

    public function count()
    {
        return $this->_feed->count();
    }

    public function rewind()
    {
        $this->_feed->rewind();
    }

    public function current()
    {
        $item = $this->_feed->current();
        // get entry reader when ready
        return $entry;
    }

    public function key()
    {
        return $this->_feed->key();
    }

    public function next()
    {
        $this->_feed->next();
    }

    abstract public function getContent();

    abstract protected function _registerDefaultNamespaces();

}