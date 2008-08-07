<?php

//

class Zend_Feed_Reader_Entry_Atom extends Zend_Feed_Reader
{

    protected $_entry = null;

    protected $_entryKey = 0;

    protected $_xpathQuery = '';
    
    protected $_data = array();

    protected $_xpath = null;

    protected $_domDocument = null;

    public function __construct(Zend_Feed_Entry_Abstract $entry, $entryKey, $type = null)
    {
        $this->_entry = $entry;
        $this->_entryKey = $entryKey;
        // Everyone by now should now XPath indices start from 1 not 0
        $this->_xpathQuery = '//entry[' . ($this->_entryKey+1) . ']';
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
        
        // @todo: create a list from all potential sources rather than from alternatives
        // There are contributors as well...
        $authors = array($this->_xpath->evaluate($this->_xpathQuery . '//author'));
        
        if ($list->length) {
            foreach ($list as $author) {
                $authors[] = $author->nodeValue;
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

    public function getContent()
    {
        if (isset($this->_data['content'])) {
            return $this->_data['content'];
        }

        $content = $this->_xpath->evaluate('string('.$this->_xpathQuery.'/content)');

        if (!$content) {
            $content = $this->getDescription();
        }

        $this->_data['content'] = $content;
        return $this->_data['content'];
    }

    public function getDescription()
    {
        // TODO: Look for an Atom equivalent of description
    }

    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }
        
        $id = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/id)');

        if (!$id) {
            //if ($this->getPermalink()) {
            //    $id = $this->getPermalink();
            if ($this->getTitle()) {
                $id = $this->getTitle();
            } else {
                $id = null;
            }
        }
        $this->_data['id'] = $id;
        return $this->_data['id'];
    }

    public function getLink($index = 0)
    {
        if (isset($this->_data['link'])) {
            return $this->_data['link'];
        }
        
        // there may be >1 links - need to return an index, or accept an index integer to fix
        $link = $this->_xpath->evaluate('string('.$this->_xpathQuery . '/link)');
        
        if (!$link) {
            $link = null;
        }
        $this->_data['link'] = $link;
        return $this->_data['link'];
    }

    public function getPermlink()
    {
        return $this->getLink(0);
    }

    public function getTitle()
    {
        if (isset($this->_data['title'])) {
            return $this->_data['title'];
        }
        
        $title = $this->_xpath->evaluate('string('.$this->_xpathQuery.'/title)');
        
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