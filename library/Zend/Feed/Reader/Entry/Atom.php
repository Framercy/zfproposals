<?php

require_once 'Zend/Feed/Reader.php';

require_once 'Zend/Feed/Reader/Entry/Interface.php';

require_once 'Zend/Feed/Reader/Author.php';
//

class Zend_Feed_Reader_Entry_Atom implements Zend_Feed_Reader_Entry_Interface
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
        $this->_xpathQuery = '//atom:entry[' . ($this->_entryKey + 1) . ']';
        $this->_domDocument = $this->_entry->getDOM()->ownerDocument;
        if (!is_null($type)) {
            $this->_data['type'] = $type;
        } else {
            $this->_data['type'] = Zend_Feed_Reader::detectType($feed);
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
        
        $authors = $this->_xpath->query($this->_xpathQuery . '//atom:author');
        $contributors = $this->_xpath->query($this->_xpathQuery . '//atom:contributor');

        $people = array();
        
        if ($authors->length) {
            foreach ($authors as $author) {
                $people[] = $this->_getAuthor($author);
            }
        }
        
        if ($contributors->length) {
            foreach ($contributors as $contributor) {
                $people[] = $this->_getAuthor($contributor);
            }
        }

        $this->_data['authors'] = $people;
        return $this->_data['authors'];
    }
    
    protected function _getAuthor(DOMElement $element)
    {
        return new Zend_Feed_Reader_Author($element->getElementsByTagName('name')->item(0)->nodeValue,
                                           $element->getElementsByTagName('email')->item(0)->nodeValue,
                                           $element->getElementsByTagName('uri')->item(0)->nodeValue);
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

        $content = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:content)');

        if (!$content) {
            $content = $this->getDescription();
        }

        $this->_data['content'] = $content;
        return $this->_data['content'];
    }
    
    public function getDateCreated()
    {
        if (isset($this->_data['datecreated'])) {
            return $this->_data['datecreated'];
        }
        
        if ($this->getType() === Zend_Feed_Reader::TYPE_ATOM_03) {
            $dateCreated = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:created)');
        } else {
            $dateCreated = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:published)');
        }
        // TODO: Make the date a Zend_Date object?
        if (!$dateCreated) {
            $dateCreated = null;
        }

        $this->_data['datecreated'] = $dateCreated;
        return $this->_data['datecreated'];
    }
    
    public function getDateModified()
    {
        if (isset($this->_data['datemodified'])) {
            return $this->_data['datemodified'];
        }

        if ($this->getType() === Zend_Feed_Reader::TYPE_ATOM_03) {
            $dateModified = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:modified)');
        } else {
            $dateModified = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:updated)');
        }
        // TODO: Make the date a Zend_Date object?
        if (!$dateModified) {
            $dateModified = null;
        }

        $this->_data['datemodified'] = $dateModified;
        return $this->_data['datemodified'];
    }

    public function getDescription()
    {
        if (isset($this->_data['description'])) {
            return $this->_data['description'];
        }

        $description = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:summary)');

        if (!$description) {
            $description = null;
        }

        $this->_data['description'] = $description;
        return $this->_data['description'];
    }

    public function getId()
    {
        if (isset($this->_data['id'])) {
            return $this->_data['id'];
        }

        $id = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:id)');

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
        if (!isset($this->_data['links'])) {
            $this->getLinks();
        }
        if (isset($this->_data['links'][$index])) {
            return $this->_data['links'][$index];
        }
        return null;
    }

    public function getLinks() 
    {
        if (isset($this->_data['links'])) {
            return $this->_data['links'];
        }
        $links = array();
        
        $list = $this->_xpath->query($this->_xpathQuery . '//atom:link/@href');
        
        if ($list->length) {
            foreach ($list as $link) {
                $links[] = $link->value;
            }
        }
        $this->_data['links'] = $links;
        return $this->_data['links'];
    }
    
    public function getPermalink()
    {
        return $this->getLink(0);
    }

    public function getTitle()
    {
        if (isset($this->_data['title'])) {
            return $this->_data['title'];
        }

        $title = $this->_xpath->evaluate('string(' . $this->_xpathQuery . '/atom:title)');

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