<?php
if (!defined('PHPUnit_MAIN_METHOD')) {

    define('PHPUnit_MAIN_METHOD', 'Zend_View_AllTests::main');

    set_include_path(
        dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR
        . dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR
        . get_include_path()
    );
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'FactoryTest.php';
require_once 'Helper/DeclareVarsTest.php';
require_once 'Helper/FormCheckboxTest.php';
require_once 'Helper/FormLabelTest.php';
require_once 'Helper/FormTextTest.php';
require_once 'Helper/HtmlListTest.php';
require_once 'Helper/PlaceholderTest.php';
require_once 'Helper/PartialTest.php';
require_once 'Helper/HeadTitleTest.php';
require_once 'Helper/HeadMetaTest.php';
require_once 'Helper/HeadScriptTest.php';
require_once 'Helper/HeadLinkTest.php';

require_once 'Helper/DoctypeTest.php';

class Zend_View_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend Framework - Zend_View');

        $suite->addTestSuite('Zend_View_FactoryTest');
        $suite->addTestSuite('Zend_View_Helper_DeclareVarsTest');
        $suite->addTestSuite('Zend_View_Helper_FormCheckboxTest');
        $suite->addTestSuite('Zend_View_Helper_FormLabelTest');
        $suite->addTestSuite('Zend_View_Helper_FormTextTest');
        $suite->addTestSuite('Zend_View_Helper_HtmlListTest');
        $suite->addTestSuite('Zend_View_Helper_PlaceholderTest');
        $suite->addTestSuite('Zend_View_Helper_PartialTest');
        $suite->addTestSuite('Zend_View_Helper_HeadTitleTest');
        $suite->addTestSuite('Zend_View_Helper_HeadMetaTest');
        $suite->addTestSuite('Zend_View_Helper_HeadScriptTest');
        $suite->addTestSuite('Zend_View_Helper_HeadLinkTest');

        $suite->addTestSuite('Zend_View_Helper_DoctypeTest');

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_View_AllTests::main') {
    Zend_View_AllTests::main();
}
