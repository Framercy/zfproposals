<?php

//

interface Zend_Feed_Reader_Feed_Interface
{
    public function getAuthors();

    public function getAuthor($index = 0);

    public function getCopyright();

    public function getDescription();

    public function getLanguage();

    public function getLink();

    public function getTitle();

    public function getUpdated();
}