<?php

class Controller
{

    /**
     * @var View
     */
    protected $_View;

    /**
     * @var array
     */
    protected $_viewData;


    public function run() {
    }

    protected function _render() {

        if ( ! isset($this->_View)) {
            // potrebujeme z nazvu ako 'Admin_ControllerLogin'  vyrobit 'Admin_ViewLogin'
            $viewName = str_replace('Controller', 'View', get_class($this));
            $this->_View = new $viewName();
        }

        $this->_View->setData($this->_viewData);
        $this->_View->render();
    }
}