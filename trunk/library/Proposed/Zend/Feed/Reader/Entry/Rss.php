<?php

//

class Zend_Feed_Reader_Entry_Rss extends Zend_Feed_Reader
{

    protected $_entry = null;

    protected $_entryKey = 0;

    protected $_xpathQueryRss = '';

    protected $_xpathQueryRdf = '';

    protected $_data = array();

    protected $_xpath = null;

    protected $_domDocument = null;

    public function __construct(Zend_Feed_Entry_Abstract $entry, $entryKey, $type = null)
    {
        $this->_entry = $entry;
        $this->_entryKey = $entryKey;
        // Everyone by now should now XPath indices start from 1 not 0
        $this->_xpathQueryRss = '//item[' . ($this->_entryKey+1) . ']';
        $this->_xpathQueryRdf = '//rss:item[' . ($this->_entryKey+1) . ']';
        $this->_domDocument = $this->_entry->getDOM()->ownerDocument;
        if (!is_null($type)) {
            $this->_data['type'] = $type;
        } else {
            $this->_data['type'] = self::detectType($feed);
        }
    }

    public function setXpath(DOMXPath $xpath)
    {
        $this->_xpath = $xpath;
    }

    public function getAuthors()
    {
        if (isset($this->_data['authors'])) {
            return $this->_data['authors'];
        }
        $authors = array();
        if ($this->getType() !== Zend_Feed_Reader::TYPE_RSS_10 && $this->getType() !== Zend_Feed_Reader::TYPE_RSS_090) {
            $list = $this->_xpath->evaluate($this->_xpathQueryRss.'//author');
            if (!$list->length) {
                $list = $this->_xpath->evaluate($this->_xpathQueryRss.'//dc11:creator');
            }
            if (!$list->length) {
                $list = $this->_xpath->evaluate($this->_xpathQueryRss.'//dc10:creator');
            }
        } else {
            $list = $this->_xpath->evaluate($this->_xpathQueryRdf.'//rss:author');
            if (!$list->length) {
                $list = $this->_xpath->evaluate($this->_xpathQueryRdf.'//dc11:creator');
            }
            if (!$list->length) {
                $list = $this->_xpath->evaluate($this->_xpathQueryRdf.'//dc10:creator');
            }
        }
        if ($list->length) {
            foreach ($list as $author) {
                if ($this->getType() == Zend_Feed_Reader::TYPE_RSS_20
                    && preg_match("/\(([^\)]+)\)/", $author->nodeValue, $matches, PREG_OFFSET_CAPTURE)) {
                    $authors[] = $matches[1][0];
                } else {
                    $authors[] = $author->nodeValue;
                }
            }
            $authors = array_unique($authors);
        }
        $this->_data['authors'] = $authors;
        return $this->_data['authors'];
    }

    public function getAuthor($index = 0)
    {
        $authors = $this->getAuthors();
        if (isset($authors[$index])) {
            return $authors[$index];
        }
        return null;
    }

    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
        $id = null;
        if ($this->getType() !== Zend_Feed_Reader::TYPE_RSS_10 && $this->getType() !== Zend_Feed_Reader::TYPE_RSS_090) {
            $id = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/guid)');
            if (!$id) {
                $id = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/dc11:identifier)');
            }
            if (!$id) {
                $id = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/dc10:identifier)');
            }
        } else {
            $id = $this->_xpath->evaluate('string('.$this->_xpathQueryRdf.'/dc11:identifier)');
            if (!$id) {
                $id = $this->_xpath->evaluate('string('.$this->_xpathQueryRdf.'/dc10:identifier)');
            }
        }
        if (!$id) {
            $id = null;
        }
        $this->_data['id'] = $id;
        return $this->_data['id'];
    }

    public function getTitle()
    {
        if (isset($this->_data['title'])) {
            return $this->_data['title'];
        }
        $title = null;
        if ($this->getType() !== Zend_Feed_Reader::TYPE_RSS_10 && $this->getType() !== Zend_Feed_Reader::TYPE_RSS_090) {
            $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/title)');
            if (!$title) {
                $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/dc11:title)');
            }
            if (!$title) {
                $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRss.'/dc10:title)');
            }
        } else {
            $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRdf.'/rss:title)');
            if (!$title) {
                $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRdf.'/dc11:title)');
            }
            if (!$title) {
                $title = $this->_xpath->evaluate('string('.$this->_xpathQueryRdf.'/dc10:title)');
            }
        }
        if (!$title) {
            $title = null;
        }
        $this->_data['title'] = $title;
        return $this->_data['title'];
    }

    public function getType()
    {
        return $this->_data['type'];
    }

    public function toArray() 
    {
        return $this->_data;
    }

    public function getDomDocument() 
    {
        return $this->_domDocument;
    }

}