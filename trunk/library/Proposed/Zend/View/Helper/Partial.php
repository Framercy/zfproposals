<?php
/**
 * Zend Framework
 *
 *
 * @package    Zend_View
 * @subpackage Helpers
 * @copyright  Copyright (c) 2007 Pádraic Brady (http://blog.astrumfutura.com)
 * @version    $Id$
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Helper for rendering a template fragment in its own variable scope.
 *
 * @package    Zend_View
 * @subpackage Helpers
 * @copyright  Copyright (c) 2007 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    New BSD
 */
class Zend_View_Helper_Partial {

    /**
     * Instance of parent Zend_View object
     *
     * @var Zend_View_Abstract
     */
    public $view = null;

    /**
     * Renders a template fragment within a variable scope distinct from the
     * calling View object.
     *
     * @param string $name
     * @param string|array $module
     * @param array $model
     * @returns string $output
     */
    public function partial($name, $module = null, array $model = null)
    {
        $viewModel = null;
        if (isset($module) && is_array($module) && !isset($model)) {
            $viewModel = $module;
            $module = null;
        } elseif (isset($model)) {
            $viewModel = $model;
        }
        if (is_null($module)) { // no point in calling a factory for a simple clone op
            $view = $this->_cloneView($viewModel);
        } else {
            $view = Zend_View_Abstract::getFactory()->createInstance($module, $viewModel, $this->view);
        }
        return $view->render($name);
    }

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_Partial
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Clone the current View within resorting to a Factory call
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_Partial
     */
    protected function _cloneView(array $viewModel = null)
    {
        $view = clone $this->view;
        $view->clearVars();
        if (!is_null($viewModel)) {
            foreach($viewModel as $key->$value) {
                $view->$key = $value;
            }
        }
        return $view;
    }

}