<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Feed_AllTests::main');
}

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'Zend/Feed/WriterTest.php';
require_once 'Zend/Feed/Writer/EntryTest.php';
require_once 'Zend/Feed/Writer/Renderer/Feed/AtomTest.php';
require_once 'Zend/Feed/Writer/Renderer/Entry/AtomTest.php';


class Zend_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend');

        $suite->addTestSuite('Zend_Feed_WriterTest');
        $suite->addTestSuite('Zend_Feed_Writer_EntryTest');
        $suite->addTestSuite('Zend_Feed_Writer_Renderer_Feed_AtomTest');
        $suite->addTestSuite('Zend_Feed_Writer_Renderer_Entry_AtomTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Feed_AllTests::main') {
    Zend_Feed_AllTests::main();
}
