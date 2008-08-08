<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Feed/Reader.php';

class Zend_Feed_Reader_Feed_AtomTest extends PHPUnit_Framework_TestCase
{

    protected $_feedSamplePath = null;

    public function setup()
    {
        $this->_feedSamplePath = dirname(__FILE__) . '/_files/Atom';
    }

    /**
     * Get Title (Unencoded Text)
     */
    public function testGetsTitleFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/atom03.xml')
        );
        $this->assertEquals('My Title', $feed->getTitle());
    }

    public function testGetsTitleFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/title/plain/atom10.xml')
        );
        $this->assertEquals('My Title', $feed->getTitle());
    }

    /**
     * Get Authors (Unencoded Text)
     */
    public function testGetsAuthorArrayFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/atom03.xml')
        );
        
        $authors = array(
            array('name'  => 'Joe Bloggs',
                  'uri'   => 'http://example.org/',
                  'email' => 'joe@example.com'),
            array('name'  => 'Jane Bloggs',
                  'uri'   => 'http://example.org/',
                  'email' => 'jane@example.com')
        );
        
        $this->assertEquals($authors, $feed->getAuthors());
    }

    public function testGetsAuthorArrayFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/atom10.xml')
        );
        
        $authors = array(
            array('name'  => 'Joe Bloggs',
                  'uri'   => 'http://example.org/',
                  'email' => 'joe@example.com'),
            array('name'  => 'Jane Bloggs',
                  'uri'   => 'http://example.org/',
                  'email' => 'jane@example.com')
        );
        
        $this->assertEquals($authors, $feed->getAuthors());
    }
    
    /**
     * Get Single Author (Unencoded Text)
     */
    public function testGetsSingleAuthorFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/atom03.xml')
        );
        $this->assertEquals(array('name'  => 'Joe Bloggs',
                                  'uri'   => 'http://example.org/',
                                  'email' => 'joe@example.com'), $feed->getAuthor());
    }

    public function testGetsSingleAuthorFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/author/plain/atom10.xml')
        );
        $this->assertEquals(array('name'  => 'Joe Bloggs',
                                  'uri'   => 'http://example.org/',
                                  'email' => 'joe@example.com'), $feed->getAuthor());
    }

    /**
     * Get Copyright (Unencoded Text)
     */
    public function testGetsCopyrightFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/copyright/plain/atom03.xml')
        );
        $this->assertEquals('Copyright 2008', $feed->getCopyright());
    }

    public function testGetsCopyrightFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/copyright/plain/atom10.xml')
        );
        $this->assertEquals('Copyright 2008', $feed->getCopyright());
    }

    /**
     * Get Description (Unencoded Text)
     */
    public function testGetsDescriptionFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/atom03.xml')
        );
        $this->assertEquals('My Description', $feed->getDescription());
    }

    public function testGetsDescriptionFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/description/plain/atom10.xml')
        );
        $this->assertEquals('My Description', $feed->getDescription());
    }

    /**
     * Get Language (Unencoded Text)
     */
    public function testGetsLanguageFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/language/plain/atom03.xml')
        );
        $this->assertEquals('en-GB', $feed->getLanguage());
    }

    public function testGetsLanguageFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/language/plain/atom10.xml')
        );
        $this->assertEquals('en-GB', $feed->getLanguage());
    }

    /**
     * Get Link (Unencoded Text)
     */
    public function testGetsLinkFromAtom03()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/atom03.xml')
        );
        $this->assertEquals('http://www.example.com', $feed->getLink());
    }

    public function testGetsLinkFromAtom10()
    {
        $feed = Zend_Feed_Reader::importString(
            file_get_contents($this->_feedSamplePath.'/link/plain/atom10.xml')
        );
        $this->assertEquals('http://www.example.com', $feed->getLink());
    }
}